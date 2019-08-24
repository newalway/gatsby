<?php

namespace ProjectBundle\Utils;

#use Liuggio\ExcelBundle\Factory;

use ProjectBundle\Utils\Collections;
use Symfony\Component\Translation\TranslatorInterface;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// use PhpOffice\PhpSpreadsheet\Style\Fill;
// use PhpOffice\PhpSpreadsheet\Style\Color;
// use PhpOffice\PhpSpreadsheet\Style\Conditional;
// use PhpOffice\PhpSpreadsheet\Style\Font;

use ProjectBundle\Entity\CustomerOrder;
use ProjectBundle\Entity\CustomerOrderItem;
use ProjectBundle\Entity\CustomerOrderDelivery;

use PHPExcel_Style_Alignment;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Color;

class ExportExcel
{
	private $kernel;
	private $index;
	private $spreadsheet;
	private $translator;

	#protected $phpexcel;
	#protected $phpExcelObject;

	public function __construct($kernel, TranslatorInterface $translator)
	{
		$this->container = $kernel->getContainer();
		$this->spreadsheet = new Spreadsheet();
		$this->index = 0;
		$this->translator = $translator;

		#$this->phpexcel = $phpexcel;
		#$this->phpExcelObject = $this->phpexcel->createPHPExcelObject();
		#$this->index = 0;
	}

	public function getExcelObjectMember()
	{
		$this->spreadsheet->getProperties()->setCreator("Num")
		->setLastModifiedBy("Num")
		->setTitle("Member")
		->setSubject("Member")
		->setDescription("Member")
		->setKeywords("Member")
		->setCategory("Member");
		$this->spreadsheet->setActiveSheetIndex($this->index)
			->setCellValue('A1', 'No.')
			->setCellValue('B1', 'Name')
			->setCellValue('C1', 'PhoneNumber')
			->setCellValue('D1', 'Email')
			->setCellValue('E1', 'Gender')
			->setCellValue('F1', 'Birth date')
			->setCellValue('G1', 'Role')
			->setCellValue('H1', 'Last login');
		$this->excelCellColor('A1:H1','404040');
		$this->excelFontColor('A1:H1','FFFFFF');
		$this->excelColunmSize('A','H');

	}

	public function exportMemberData($members)
	{
		$i=2; //row number
		$order_no=1; //order index
		if(!$members){
			throw $this->createNotFoundException('This data doesn\'t exist');
		}

		foreach ($members as $member){
			$this->setExcelRowMember($i, $member, $order_no);

			if(($order_no%2)==0){
				$this->excelCellColor('A'.$i.':S'.$i, 'EEEEEE');
			}
			$i++;
			$order_no++;
		}//endfor
	}

	protected function setExcelRowMember($i, $member, $order_no)
	{
		$user_id = $member->getId();

		$data_gender = Collections::wordGender($member->getGender());
		$data_birthdate = $member->getBirthDate();
		if($data_birthdate){
			$data_birthdate = $data_birthdate->format('d F Y');
		}
		$data_lastlogin = $member->getLastLogin();
		if($data_lastlogin){
			$data_lastlogin = $data_lastlogin->format('d/m/Y H:i:s');
		}

		$this->spreadsheet->setActiveSheetIndex($this->index)
			->setCellValue('A'.$i, $order_no)
			->setCellValue('B'.$i, $member->getFirstName().' '.$member->getLastName())
			->setCellValue('C'.$i, $member->getPhoneNumber())
			->setCellValue('D'.$i, $member->getEmail())
			->setCellValue('E'.$i, $data_gender)
			->setCellValue('F'.$i, $data_birthdate)
			->setCellValue('G'.$i, Collections::getCustomerPlan($member))
			->setCellValue('H'.$i, $data_lastlogin);
	}

	public function getExcelResponse()
	{
		$writer = $this->phpexcel->createWriter($this->spreadsheet, 'Excel5');
		$response = $this->phpexcel->createStreamedResponse($writer);
		return $response;
	}

	public function saveExcelFile($source_path, $file_name)
	{
		$writer = $this->phpexcel->createWriter($this->spreadsheet, 'Excel5');
		$writer->save($source_path.$file_name);
		return $file_name;
	}

	public function excelCellColor($cells, $color)
	{
		// $this->spreadsheet->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
		// 	'type' => PHPExcel_Style_Fill::FILL_SOLID,
		// 	'startcolor' => array(
		// 		'rgb' => $color
		// 	),
		// ));

		$this->spreadsheet->getActiveSheet()->getStyle($cells)->getFill()
		->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
		->getStartColor()->setARGB($color);

	}
	public function excelFontColor($cells, $color)
	{
		$this->spreadsheet->getActiveSheet()->getStyle($cells)
		->getFont()->getColor()->setARGB($color);
	}
	function excelColunmSize($columnStart,$columnEnd){
		foreach(range($columnStart,$columnEnd) as $columnID) {
    		$this->spreadsheet->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
		}
	}
	function excelAccountingFormat($column){
		$this->spreadsheet->getActiveSheet()->getStyle($column)
		->getNumberFormat()->setFormatCode('_(""* #,##0.00_);_(""* \(#,##0.00\);_(""* "-"??_);_(@_)');
	}




	# for spreadsheet
	public function saveOutputExcel($filename='export.xlsx')
	{
		// // save export file
		$source_path = $this->container->getParameter('source_data_export_path');
		$writer = new Xlsx($this->spreadsheet);
		$writer->save($source_path.$filename);
	}

	public function streamOutputExcel($filename='export.xlsx')
	{
		// // redirect output to client browser
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$writer = new Xlsx($this->spreadsheet);
		$writer->save('php://output');
		exit;

		// // https://phpspreadsheet.readthedocs.io/en/develop/topics/recipes/
		// $writer = IOFactory::createWriter($this->spreadsheet, 'Xls');
		// $writer->save('php://output');
	}

	public function getHeaderExcelOrder(){

		$this->spreadsheet->setActiveSheetIndex($this->index)
				->setCellValue('A1', 'No')
			  	->setCellValue('B1', 'Order Number')
			  	->setCellValue('C1', 'Order Date')
				->setCellValue('D1', 'Order Time')
			  	->setCellValue('E1', 'Customer Name')
				->setCellValue('F1', 'Email')
				->setCellValue('G1', 'Phone')

				->setCellValue('H1', 'Delivery Address')

			  	->setCellValue('I1', 'Status')
			  	->setCellValue('J1', 'Method')
			  	->setCellValue('K1', 'Shipment')

				->setCellValue('L1', 'SKU')
				->setCellValue('M1', 'Product Name')
				->setCellValue('N1', 'Variant')

				->setCellValue('O1', 'Price')
				->setCellValue('P1', 'Quantity')
				->setCellValue('Q1', 'Amount')
				->setCellValue('R1', 'subTotal')
				->setCellValue('S1', 'Discount Code')
				->setCellValue('T1', 'Discount Amount')
				->setCellValue('U1', 'Total');
				//->setCellValue('Q1', 'Amount');

		// 	$this->spreadsheet->getActiveSheet()->getStyle('B3:B7')->getFill()
	    // ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	    // ->getStartColor()->setARGB('FFFF0000');
	}

	public function setStyleAndFormatExcelOrder() {
		$this->excelColunmSize('A','U');
		$this->excelAccountingFormat('O');
		$this->excelAccountingFormat('Q');
		$this->excelAccountingFormat('R');
		$this->excelAccountingFormat('T');
		$this->excelAccountingFormat('U');
		$this->excelCellColor('A1:U1','404040');
		$this->excelFontColor('A1:U1','FFFFFF');
		$this->excelCellColor('A1','404040');
	}

	public function getHeaderExcelOrderItems(){
		$this->spreadsheet->setActiveSheetIndex($this->index)
				->setCellValue('A4', 'No')
				->setCellValue('B4', 'Product Name')
				->setCellValue('C4', 'Price')
				->setCellValue('D4', 'Quantity')
				->setCellValue('E4', 'Total');
				$this->excelCellColor('A4:E4','404040');
				$this->excelFontColor('A4:E4','FFFFFF');
	}

	public function setDataExcelOrder($orders)
	{
		$i=2; //row number
		$order_no=1; //order index
		$start = $i;
		$sum_total_price = 0;

		if(!$orders){
			throw $this->createNotFoundException('This data doesn\'t exist');
		}

		$repo_order_item = $this->container->get('doctrine')->getRepository(CustomerOrderItem::class);
		$repo_order_delivery = $this->container->get('doctrine')->getRepository(CustomerOrderDelivery::class);

		$payment_status_paid = $this->container->getParameter('payment_status_paid');
		$payment_status_unpaid = $this->container->getParameter('payment_status_unpaid');

		foreach ($orders as $order){
			$orderNumber = $order->getOrderNumber();
			$order_date = $order->getOrderDate()->format('d/n/Y');
			$order_time = $order->getOrderDate()->format('H:i');

			$status = ($order->getPaid()) ? $payment_status_paid : $payment_status_unpaid;
			// $status = '=IF('.$order->getPaid().'=1,"'.$payment_status_paid.'","'.$payment_status_unpaid.'")';

			$customer_name = $order->getUser()->getFirstName()." ".$order->getUser()->getLastName();
			$user_phone = $order->getUser()->getPhoneNumber();
			$email = $order->getUser()->getEmail();

			$payment = $order->getPaymentOption();
			$shipDate = ($order->getShipDate()) ? $order->getShipDate()->format('d/n/Y') : null;
			// $shipDate= $order->getShipDate()->format('d/n/Y');
			$shippingCost = $order->getShippingCost();
			$DiscountCode = $order->getDiscountCode();
			$DiscountAmount	= $order->getDiscountAmount();

			$sub_totalPrice = $order->getSubTotal();
			$totalPrice = $order->getTotalPrice();

			$this->spreadsheet->setActiveSheetIndex($this->index)
				->setCellValue('A'.$i, $order_no)
				->setCellValue('B'.$i, $orderNumber)
				->setCellValue('C'.$i, $order_date)
				->setCellValue('D'.$i, $order_time)
				->setCellValue('E'.$i, $customer_name)
				->setCellValue('F'.$i, $email)

				->setCellValue('I'.$i, $status)
				->setCellValue('J'.$i, $payment)
				->setCellValue('K'.$i, $shipDate)

				->setCellValue('R'.$i, $sub_totalPrice)
				->setCellValue('S'.$i, $DiscountCode)
				->setCellValue('T'.$i, $DiscountAmount)
				->setCellValue('U'.$i, $totalPrice)
			;

			$deliveryAddresss = $repo_order_delivery->findCustomerOrderDeliveryByOrderAndType($order, 1)->getQuery()->getResult();
			if($deliveryAddresss){
				foreach ($deliveryAddresss as $deliveryAddress){
					 $address = $deliveryAddress->getAddress();
					 $district = $deliveryAddress->getDistrict();
					 $province =$deliveryAddress->getProvince();
					 //$country = $deliveryAddress->getCountry();
					 $postCode = $deliveryAddress->getPostCode();
					 $dlivery_phone = $deliveryAddress->getPhone();
					 $full_address = $address.", ".$district.", ".$province." ".$postCode;
					 $this->spreadsheet->setActiveSheetIndex($this->index)
					 	->setCellValue('G'.$i, $dlivery_phone)
					 	->setCellValue('H'.$i, $full_address);
				}
			}

			$order_items = $repo_order_item->findByCustomerOrder($order);
			if($order_items){
				foreach ($order_items as $items) {
					$itemName = $items->getProductTitle();
					$itemPrice = $items->getPrice();
					$itemQuantity = $items->getQuantity();
					$itemAmount = $items->getAmount();
					$sku_value = $items->getSkuValue();
					$variants = $items->getSkuTitle();
					$str_variant = ($variants) ? join(' · ', $variants) : '' ;

				  	$this->spreadsheet->setActiveSheetIndex($this->index)
						->setCellValue('L'.$i, $sku_value)
						->setCellValue('M'.$i, $itemName)
						->setCellValue('N'.$i, $str_variant)
						->setCellValue('O'.$i, $itemPrice)
						->setCellValue('P'.$i, $itemQuantity)
						->setCellValue('Q'.$i, $itemAmount);
					$i++;
				}
			}
			// $sum_total_price = $sum_total_price+$totalPrice;

			// $i++; //new line end of order
			$order_no++;

		}//endfor

		// $this->spreadsheet->getActiveSheet($this->index)->setCellValue('R'.$i, $sum_total_price);

		$end_sum = $i-1;
		$this->spreadsheet->getActiveSheet($this->index)->setCellValue('T'.$i,'Grand Total');
		$this->spreadsheet->getActiveSheet($this->index)->setCellValue('U'.$i,'=SUM(U'.$start.':U'.$end_sum.')');

	}

	public function setDataExcelOrderAndItems($orders,$payment_bank_transfer)
	{
		/*
		$i=2; //row number order
		$order_no=1; //order index
		$j = 5;//row number item
		$item_no = 1;//item index
		if(!$orders){
			throw $this->createNotFoundException('This data doesn\'t exist');
		}

		$payment_status_paid = $this->container->getParameter('payment_status_paid');
		$payment_status_unpaid = $this->container->getParameter('payment_status_unpaid');

		foreach ($orders as $order){
				$status = '=IF('.$order->getPaid().'=1,"'.$payment_status_paid.'","'.$payment_status_unpaid.'")';
			$this->spreadsheet->setActiveSheetIndex(0)
				  ->setCellValue('A'.$i, $order_no)
				  ->setCellValue('B'.$i, $order->getOrderNumber())
				  ->setCellValue('C'.$i, $order->getOrderDate()->format('d F Y'))
				  ->setCellValue('D'.$i, $order->getUser()->getFirstName()." ".$order->getUser()->getLastName())
				  ->setCellValue('E'.$i, $status)
				  ->setCellValue('F'.$i, $order->getPaymentOption())
				  ->setCellValue('G'.$i, $order->getShipDate()->format('d F Y'))
				  ->setCellValue('H'.$i, $order->getTotalPrice());

				 $i++;
				 $order_no++;



			  $this->spreadsheet->createSheet();
			  $this->spreadsheet->setActiveSheetIndex(1)->setTitle('product');

			foreach ($order->getCustomerOrderItems() as $items){
			$this->spreadsheet->setActiveSheetIndex(1)
					 ->setCellValue('A'.$j, $item_no)
					 ->setCellValue('B'.$j, $items->getProductTitle())
					 ->setCellValue('C'.$j, $items->getPrice())
					 ->setCellValue('D'.$j, $items->getQuantity())
					 ->setCellValue('E'.$j, $items->getAmount());
					 $j++;
					 $item_no++;
			 }
		}//endfor

		if ($payment_bank_transfer){
		// 	foreach ($payment_bank_transfer as $payment){
		//
		// 		$this->spreadsheet->setActiveSheetIndex($this->index)
		// 			  ->setCellValue('A'.$j, $item_no)
		// 			  ->setCellValue('B'.$j, $order->getOrderNumber())
		// 			  ->setCellValue('C'.$j, $order->getOrderDate()->format('d F Y'))
		// 			  ->setCellValue('D'.$j, $order->getUser()->getFirstName()." ".$order->getUser()->getLastName())
		// 			  ->setCellValue('E'.$j, $status)
		// 			  ->setCellValue('F'.$j, $order->getPaymentOption())
		// 			  ->setCellValue('G'.$j, $order->getShipDate()->format('d F Y'))
		// 			  ->setCellValue('H'.$j, $order->getTotalPrice());
		// 			 $j++;
		// 			 $item_no++;
		// 	}//endfor
		 }
		 */
	}
}
