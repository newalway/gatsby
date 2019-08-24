function updateCartBoxData(element){
	var link_product_detail = img_box_html = variant_option = title_amount_html = price_html = quantity_html = '';
	var newLiElement = angular.element('<li></li>');
	var TitleBoxEle = angular.element('<div class="text-box"></div>');

	link_product_detail = Routing.generate('product_detail', { id:element.product_id, slug:slugify(element.title) });

	if(element.image_small){
		img_box_html = '<div class="img-box"><img src="'+element.image_small+'" alt="'+element.title+'" /></div>';
	}else{
		img_box_html = '<div class="img-box"><img src="/template/img/resources/header-cart-1.jpg" alt="'+element.title+'" /></div>';
	}

	if(element.variant_option.length>0){
		variant_option = element.variant_option.join(" · ");
	};

	title_amount_html = '<a href="'+link_product_detail+'"><h3>'+element.title+'</h3> '+variant_option+' </a>';
	price_html = '<span class="price">'+ numeral(element.amount).format('0,0') +'฿</span>';
	TitleBoxEle.append(title_amount_html);
	TitleBoxEle.append(price_html);
	if(element.quantity>1){
		quantity_html = '<div class="review-box"> <i>'+numeral(element.price).format('0,0')+'</i>฿ (Qty: '+element.quantity+') </div>';
		TitleBoxEle.append(quantity_html);
	}

	newLiElement.append(img_box_html);
	newLiElement.append(TitleBoxEle);
	angular.element($( "#cart_list_products_item" ).append(newLiElement));
}
