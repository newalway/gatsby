app.controller('deliveryAddressCtrl',['$scope', '$sce', 'product_data', function($scope, $sce, product_data){
	$scope.delivery_init = function(default_first_name, default_last_name, default_company_name, default_phone)
	{
		// get data from window object
		$scope.delivery_address = window.delivery_address;

		$scope.model_addr = {};
		$scope.shipping_address = $scope.arr_cart_data.delivery_information.shipping_address;
		$scope.billing_address = $scope.arr_cart_data.delivery_information.billing_address;

		$scope.frm_delivery = {
			first_name: default_first_name,
			last_name: default_last_name,
			company_name: default_company_name,
			phone: default_phone
		};
		$scope.frm_delivery_error = {};

		$scope.frm_first_delivery = {
			first_name: default_first_name,
			last_name: default_last_name,
			company_name: default_company_name,
			phone: default_phone
		};
		$scope.frm_first_delivery_error = {};

		// $scope.message_two = '';
	}

	$scope.selectShippingAddress = function()
	{
		$scope.model_addr.mode = 'shipping_address';
		$scope.model_addr.selected_delivery_address_id = $scope.shipping_address.id;
	}
	$scope.selectBillingAddress = function()
	{
		$scope.model_addr.mode = 'billing_address';
		$scope.model_addr.selected_delivery_address_id = $scope.billing_address.id;
	}

	$scope.changeDeliveryAddress = function()
	{
		product_data.updateCheckoutDeliveryAddress($scope.model_addr.mode, $scope.model_addr.selected_delivery_address_id).then(function onSuccess(response){
			if(response.data.arr_result.status){
				$scope.emitEvent(response.data.arr_cart_data.delivery_information);
				if(response.data.arr_result.mode=='shipping_address'){
					$scope.shipping_address = response.data.arr_cart_data.delivery_information.shipping_address;
				}else if(response.data.arr_result.mode=='billing_address'){
					$scope.billing_address = response.data.arr_cart_data.delivery_information.billing_address;
				}
			}else{
				//error
			}

			//close modal list-address-billing
			angular.element($('#list-address-billing').modal('toggle'));

		}).catch(function onError(response) {
		});
	}

	$scope.submitDeliveryAddressForm = function(tmp_country_code)
	{
		$scope.frm_delivery._token = angular.element('#delivery_address__token').val();
		$scope.frm_delivery.mode = $scope.model_addr.mode;

		//temp fix overide countrycode
		if(tmp_country_code){
			$scope.frm_delivery.countryCode = tmp_country_code;
		}

		product_data.addCheckoutDeliveryAddress($scope.frm_delivery).then(function onSuccess(response){
			if(response.data.arr_result.status){
				$scope.frm_delivery_error = {};
				$scope.emitEvent(response.data.arr_cart_data.delivery_information);
				$scope.delivery_address = response.data.arr_delivery_address;
				if(response.data.arr_result.mode=='shipping_address'){
					$scope.shipping_address = response.data.arr_cart_data.delivery_information.shipping_address;
				}else if(response.data.arr_result.mode=='billing_address'){
					$scope.billing_address = response.data.arr_cart_data.delivery_information.billing_address;
				}

				$scope.frm_delivery = {};
				//close modal exampleModal
				angular.element($('#exampleModal').modal('toggle'));

			}else{
				$scope.frm_delivery_error = response.data.arr_result.errors;
			}
		}).catch(function onError(response) {
			// closeDomWindow();
		});
	}

	$scope.submitCreateFirstDeliveryAddressForm = function(tmp_country_code)
	{
		$scope.frm_first_delivery._token = angular.element('#delivery_address__token').val();
		$scope.frm_first_delivery.mode = 'first_delivery_address';

		//temp fix overide countrycode
		if(tmp_country_code){
			$scope.frm_first_delivery.countryCode = tmp_country_code;
		}

		product_data.addCheckoutDeliveryAddress($scope.frm_first_delivery).then(function onSuccess(response){

			if(response.data.arr_result.status){
				$scope.frm_first_delivery_error = {};
				$scope.emitEvent(response.data.arr_cart_data.delivery_information);
				$scope.delivery_address = response.data.arr_delivery_address;

				$scope.shipping_address = response.data.arr_cart_data.delivery_information.shipping_address;
				$scope.billing_address = response.data.arr_cart_data.delivery_information.billing_address;

				$scope.frm_first_delivery = {};

			}else{
				$scope.frm_first_delivery_error = response.data.arr_result.errors;
			}

		}).catch(function onError(response) {
			// closeDomWindow();
		});
	}


	// // // Send an event all the way up.
	$scope.emitEvent = function(delivery_information){
		$scope.$emit('updateDeliveryInformationEvent', {delivery_information: delivery_information});
	}

	// // // Listen to 'broadcastEvent'
	// $scope.$on('broadcastEvent', function(event, data){
	// 	$scope.message_two = data.message;
	// });

}]);
