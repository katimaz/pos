<?php

namespace App\Http\Controllers;

use App\Order;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use DB;
use Illuminate\Http\Request;

class ExcelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function exportSalesPage(){
        return view('admin.report.sales');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function exportSales(Request $request){

        $from_date = $request->from_date;
        if($from_date == null){
            $from_date = '1990-01-01';
        }
        $to_date = $request->to_date;
        if($to_date == null){
            $to_date = '2099-01-01';
        }

        $orders = DB::table('orders')
            ->where('orders.separate','=',null)
            ->whereBetween('orders.order_date',[$from_date,$to_date])
            ->get();

        $orderSubProducts = DB::table('order_sub_products')
            ->join('products','products.id','order_sub_products.sub_product_id')
            ->join('categories','categories.id','products.category_id')
            ->select('order_sub_products.*','categories.name as category_name')
            ->where('sale_check','=','Y')
            ->orderBy('categories.priority','asc')
            ->get();

        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load(public_path('template/sale.xlsx'));

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C1', $from_date);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E1', $to_date);
        $row = 3;
        foreach ($orders as $key => $order){
            $model = '';
            $order_no = $order->order_no;
            if($order->order_type !='S') {
                $order_no = $order->order_no . $order->order_type;
            }
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$row, $order->order_date);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$row, $order_no);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$row, $order->customer_name);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$row, $order->customer_phone);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$row, "(".$order->order_currency.") ".$order->total_price);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$row, $order->referral);

            $orderProducts = DB::table('order_products')
                ->join('products','products.id','order_products.product_id')
                ->join('categories','categories.id','products.category_id')
                ->select('order_products.*','categories.name as category_name')
                ->where('order_products.order_id','=',$order->id)
                ->orderBy('categories.priority','asc')
                ->get();

            foreach ($orderProducts as $i => $orderProduct) {

                if ($i == 0) {
                    if($orderProduct->product_quantity > 1){
                        $model .= $orderProduct->product_model_no."*".$orderProduct->product_quantity;
                    }else{
                        $model .= $orderProduct->product_model_no;
                    }
                } else {
                    if($orderProduct->product_quantity > 1){
                        $model .= "+" . $orderProduct->product_model_no."*".$orderProduct->product_quantity;
                    }else{
                        $model .= "+" . $orderProduct->product_model_no;
                    }
                }


                foreach ($orderSubProducts as $index => $orderSubProduct) {
                    if ($orderProduct->id == $orderSubProduct->order_product_id) {
                        if($orderSubProduct->sub_product_quantity > 1){
                            $model .= "+" . $orderSubProduct->sub_product_model_no."*".$orderSubProduct->sub_product_quantity;
                        }else{
                            $model .= "+" . $orderSubProduct->sub_product_model_no;
                        }
                        /**break;**/
                    }
                }
            }

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$row, $model);
            $row++;
        }

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        header('Content-Disposition: attachment;filename='.trans('reports.sale.title').'.xlsx');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function exportDeviceSalesPage(){
        return view('admin.report.devicesales');
    }

    public function exportDeviceSale(Request $request){

        $from_date = $request->from_date;
        if($from_date == null){
            $from_date = '1990-01-01';
        }
        $to_date = $request->to_date;
        if($to_date == null){
            $to_date = '2099-01-01';
        }

        $orders = DB::table('orders')
            ->join('order_products','order_products.order_id','orders.id')
            ->join('products','products.id','order_products.product_id')
            ->join('categories','categories.id','products.category_id')
            ->where('orders.separate','=',null)
            ->where('products.device_check','=','Y')
            ->where('orders.order_type','=','S')
            ->whereBetween('orders.order_date',[$from_date,$to_date])
            ->select('orders.id as ordersid' ,'orders.*','order_products.id as order_productsid', 'order_products.*','categories.name as category_name','products.maintenance_period as maintenance_period')
            ->get();

        $orderIdArray = [];
        foreach ($orders as $order) {
            array_push($orderIdArray,$order->ordersid);
        }

        $orderProductsIds = DB::table('orders')
            ->join('order_products','order_products.order_id','orders.id')
            ->whereIn('orders.id',$orderIdArray)
            ->select('order_products.id')
            ->get();

        $orderProductsIdArray = [];
        foreach ($orderProductsIds as $orderProductsId) {
            array_push($orderProductsIdArray,$orderProductsId->id);
        }

        $orderSubProducts = DB::table('order_sub_products')
            ->join('products','products.id','order_sub_products.sub_product_id')
            ->join('categories','categories.id','products.category_id')
            ->whereIn('order_sub_products.order_product_id',$orderProductsIdArray)
            ->where('products.device_check','=','Y')
            ->select('order_sub_products.*','categories.name as category_name')
            ->get();

        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load(public_path('template/device_sale_template.xlsx'));

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C1', $from_date);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E1', $to_date);
        $row = 3;
        foreach ($orders as $key => $order){

            $maintenance_date = date('Y-m-d', strtotime($order->order_date . "+".$order->maintenance_period." months") );

            if(is_null($order->product_model_no)){
                $item_name = $order->product_name;
            }else{
                $item_name = $order->product_model_no;
            }

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$row, $order->order_date);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$row, $order->customer_name);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$row, $item_name);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$row, $order->customer_phone);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$row, $order->product_serial_no);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$row, $maintenance_date);

            foreach ($orderSubProducts as $index => $orderSubProduct){
                if($order->order_productsid == $orderSubProduct->order_product_id){
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$row, $orderSubProduct->sub_product_serial_no);
                    break;
                }
            }
            $row++;
        }

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename='.trans('reports.device.sale.title').'.xlsx');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;

    }

    public function index($id)
    {
        $orders = DB::table('orders')
            ->join('order_products','order_products.order_id','orders.id')
            ->join('products','products.id','order_products.product_id')
            ->join('categories','categories.id','products.category_id')
            ->where('orders.id',$id)
            ->select('orders.id as ordersid' ,'orders.*','order_products.id as order_productsid', 'order_products.*','categories.name as category_name')
            ->orderBy('categories.priority','asc')
            ->get();

        $orderProductsIds = DB::table('orders')
            ->join('order_products','order_products.order_id','orders.id')
            ->where('orders.id',$id)
            ->select('order_products.id')
            ->get();

        $orderProductsIdArray = [];
        foreach ($orderProductsIds as $orderProductsId) {
            array_push($orderProductsIdArray,$orderProductsId->id);
        }

        $orderSubProducts = DB::table('order_sub_products')
            ->join('products','products.id','order_sub_products.sub_product_id')
            ->join('categories','categories.id','products.category_id')
            ->whereIn('order_sub_products.order_product_id',$orderProductsIdArray)
            ->select('order_sub_products.*','categories.name as category_name')
            ->orderBy('categories.priority','asc')
            ->get();

        if($orders[0]->order_type !="S"){
            $order_no = $orders[0]->order_no.$orders[0]->order_type;
        }else{
            $order_no = $orders[0]->order_no;
        }

        $reader = IOFactory::createReader('Xlsx');
        if($orders[0]->order_type == 'R'){
            $spreadsheet = $reader->load(public_path('template/rental_invoice.xlsx'));
        }else{
            $spreadsheet = $reader->load(public_path('template/invoice.xlsx'));
        }
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F3', $orders[0]->order_date);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F4', $order_no);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A10', $orders[0]->customer_name);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A11', $orders[0]->customer_address);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A13', $orders[0]->customer_country_name);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A14', $orders[0]->customer_phone);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E38', "TOTAL(".$orders[0]->order_currency.")");

        $row = 17;
        foreach ($orders as $key => $order){
            $product_price = ($order->product_price*$order->product_quantity)*$order->order_currency_ratio;
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$row, $order->product_name.$order->product_serial_no);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$row, $order->product_quantity);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$row, $order->category_name);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$row, $product_price);

            foreach ($orderSubProducts as $index => $orderSubProduct){
                if($order->order_productsid == $orderSubProduct->order_product_id){
                    $row++;
                    $sub_product_price = ($orderSubProduct->sub_product_price*$orderSubProduct->sub_product_quantity)*$order->order_currency_ratio;
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$row, "   ".$orderSubProduct->sub_product_name.$orderSubProduct->sub_product_serial_no);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$row, $orderSubProduct->sub_product_quantity);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$row, $orderSubProduct->category_name);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$row, $sub_product_price);
                }
            }
            $row++;
        }

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        header('Content-Disposition: attachment;filename="'.$order_no.'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}
