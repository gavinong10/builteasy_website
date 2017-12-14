function discount_rsFn(){
	
		var disc_val=($("#discount_rs").val())?$("#discount_rs").val():0;
		var payment_plan=$("#payment").val();
	
		if(payment_plan=='discountplan'){
			var a=document.regform.total_cost.value;
			a=(a)?a:0;
			var b=document.regform.discount_per.value;
			b=(b)?b:0;
			var c=(a*b)/100;
			document.regform.after_disaccount.value=c;
			var final_amount=a-c;
			document.regform.down_payment.value=(final_amount-disc_val);
		}else if(payment_plan=='fullpayment'){

			var a=document.regform.total_cost.value;
			a=(a)?a:0;
			document.regform.down_payment.value=(a-disc_val);
			
		}else if(payment_plan=='installment'){
				var total_cost=($("#total_cost").val())?parseFloat($("#total_cost").val()):0;

				var down_payment=($("#down_payment").val())?parseFloat($("#down_payment").val()):0;

				var loan=(total_cost-down_payment)-disc_val;

				/////alert(parseFloat(discount_rs_val));

				$("#loan_amount").val(parseFloat(loan));
		}	
}
  
jQuery(document).ready(function()
		{ 

			$("#installment_plan").hide();

			$("#no_of_installment").hide();

			$("#discount").hide();

			$(".oneform").hide();
					 
			
			/*By Default custoemr/dealer Form show Hide*/
			var type=$("#type").val();

			if(type=='0')
			{
				$(".oneform").show();
				$(".bankdetails").hide();
			}
			else if(type=='1')
			{
				$(".bankdetails").show();
				$(".oneform").hide();
			}
			else
			{
				$(".bankdetails").hide();
				$(".oneform").hide();
			}

			 /* for customer/dealer form on change*/

		   $("#type").live("change",function(){

			   var type=$("#type").val();
			   if(type=='0')
			   { 
					$(".oneform").show();
					$(".bankdetails").hide();
			   }
			   else if(type=='1')
			   { 
					$(".oneform").hide();
					$(".bankdetails").show();
			   }
			   else
			   {
				   	$(".oneform").hide();
					$(".bankdetails").hide();
			   }

			});

			/*End*/
		

		/*For payment mode if cheque is selected show div*/
		  var payment_mode=$("#payment_mode").val();
		  
		   if(payment_mode=='cheque')
			  {
				  $('.cheque_div').show();
				  $("#cheque_amount").val($("#down_payment").val());
				  $('.add_rows_div').show();
				  $('.cash_div').hide();   
				  $('.cashamount_div').hide();  
				  $('.single_cash_div').hide();  
				  $('.single_inner_cash_tr').hide(); 
				  $("#cheque_no").hide();  
				  $('.neft_div').hide();
			  }
			  else if(payment_mode=='cash')
			  {
				  $('.cheque_div').hide();
				  $("#cash_amount").val($("#down_payment").val());
				  $('.add_rows_div').hide(); 
				  $('.cash_div').show();  
				  $('.cashamount_div').show(); 
				  $('.single_cash_div').hide();  
				  $('.single_inner_cash_tr').hide(); 
				  $("#cheque_no").hide(); 
				  $('.neft_div').hide();
			  }
			   else if(payment_mode=='singlecheque')
			  {		
				  $('.neft_div').hide();
				  $('.cheque_div').hide();
				  $('.add_rows_div').hide(); 
				 
				  $('.cash_div').hide();  
				  $('.cashamount_div').hide();   
				  $('.single_cash_div').show();  
				  $('.single_inner_cash_tr').show();
				  $("#cheque_no").show();     
			  }else if( payment_mode=='NEFT' || payment_mode=='RTGS'){
				 
				 $(".cheque_amount").val($("#down_payment").val());
				 $('.cheque_div').hide();
				 $('.add_rows_div').hide(); 
				 $('.cash_div').hide();  
				 $('.cashamount_div').hide();   
				 $('.single_cash_div').hide();
				 $('.neft_div').show();
			  }	
			  else
			  {		
				  $('.neft_div').hide();
				  $('.cheque_div').hide();
				  $('.add_rows_div').hide(); 
				  $('.cash_div').hide();  
				  $('.cashamount_div').hide(); 
				   $('.single_cash_div').hide();
				   $('.single_inner_cash_tr').hide();   
				   $('#cheque_no').hide();
			  }
		  
		  //when payment_mode select box is changed
		$('#payment_mode').live("change",function(){			 
			  var payment_mode=$("#payment_mode").val();
			  if(payment_mode=='cheque')
			  {
				  $('.cheque_div').show();
				  $(".cheque_amount").val($("#down_payment").val());
				  $('.add_rows_div').show();
				  $('.cash_div').hide();   
				  $('.cashamount_div').hide();  
				  $('.single_cash_div').hide();  
				  $('.single_inner_cash_tr').hide(); 
				  $("#cheque_no").hide();
				  $('.neft_div').hide();
			  }
			  else if(payment_mode=='cash')
			  {  
				  $('.cheque_div').hide();
				  $('.add_rows_div').hide(); 
				  $('.cash_div').show();  
				  $('.cashamount_div').show(); 
				  $("#cash_amount").val($("#down_payment").val());
				    $('.single_cash_div').hide();  
				   $('.single_inner_cash_tr').hide(); 
				   $("#cheque_no").hide(); 
				   $('.neft_div').hide();
			  }
			  else if(payment_mode=='singlecheque')
			  {	
				  $('.cheque_div').hide();
				  $('.add_rows_div').hide(); 
				  $('.cash_div').hide();  
				  $('.cashamount_div').hide();
				   $('.neft_div').hide();
				   $('.single_cash_div').show();  
				   $('.single_inner_cash_tr').show();  
				   $("#cheque_no").show();  

			  }else if( payment_mode=='NEFT' || payment_mode=='RTGS'){
				 $('.cheque_div').hide();
				  $('.add_rows_div').hide(); 
				  $('.cash_div').hide();  
				  $(".cheque_amount").val($("#down_payment").val());
				  $('.cashamount_div').hide();   
				   $('.single_cash_div').hide();
				   $('.neft_div').show();
			  }			  
			  else
			  {
				  $('.cheque_div').hide();
				  $('.add_rows_div').hide(); 
				  $('.cash_div').hide();  
				  $('.cashamount_div').hide(); 
				   $('.single_cash_div').hide();  
				   $('.single_inner_cash_tr').hide();   
				   $('.neft_div').hide();
			  }
	 });
		
		/*End for payment mode*/	
		
		/*Formula To calculate EMI*/

			$("#roi_per_annum").keyup(function(){

				<!--EMI Calculation-->

				var roipa=$("#roi_per_annum").val();

				var roipm=roipa/1200;

				$("#roi_per_month").val(roipm);

				var total_loan=$("#loan_amount").val();

				var no_of_installment=$("#installment_plan").val();
				
				if(no_of_installment=='others')
				{
					var no_of_installment=$("#no_of_installment").val();
				}

				/*EMI Formula*/
				var emi_amount=(total_loan*roipm) / (1-(Math.pow(1/(1+roipm),no_of_installment)));

				$("#emi").val(Math.round(emi_amount));
			 
			  var total_loan_with_intrest = (emi_amount*no_of_installment);
			  $("#total_loan_amount").val(total_loan_with_intrest);

			});

		/*End of EMI Calculation Formula*/

			/*To calculate loan amount after downpayment*/

			$("#down_payment").keyup(function(){
	
				/*var total_cost=parseFloat($("#total_cost").val());

				var down_payment=parseFloat($("#down_payment").val());
								
				var discount_rs_val=($("#discount_rs").val())?$("#discount_rs").val():0;

				var loan=(total_cost-down_payment)-discount_rs_val;

				/////alert(parseFloat(discount_rs_val));

				$("#loan_amount").val(parseFloat(loan));*/
				
				getDownpaymentBalance();

			});

			/*End Downpayment section*/

			/* for payment mode*/

			var payment=$("#payment").val();
			if(payment=='installment')
			{ 
			   $("#installment_plan").show();
			   
			   $(".emi_div").show();
			   
			   var installment_plan=$("#installment_plan").val();

			   if(installment_plan=='others')
			   {
				  $("#no_of_installment").show();
			   }

			$("#discount").hide();
			}
			
			else if(payment=='fullpayment')
			{ 
			   $("#discount").show();
			   $(".emi_div").hide();
			}
			else if(payment=='discount_percent')
			{ 
			  $("#installment_plan").hide();
			   
			   $("#discount").hide();
			   $(".emi_div").hide();
			   $("#discount_per").show();
			}
			else
			{
				  $(".emi_div").hide();
			}


		   $("#payment").live("change",function(){
           		
			 var payment=$("#payment").val();
            
			   if(payment=='discountplan')
			{ 
			
			   $("#discount_per").show();
			}
			   if(payment=='installment')
			   {
			   		$("#installment_plan").show();
					$("#discount").hide();
					$(".emi_div").show();
				   $("#down_payment").val("");
				   $("#down_payment").attr('readonly', false);
				   $("#discount_per").hide();
				discount_rsFn();
			   }
			   if(payment!='installment')
			   {
			   		$("#installment_plan").hide();
					$("#no_of_installment").hide();
					$(".emi_div").hide();
					$("#discount_per").hide();
					 $("#discount_per").show();
				discount_rsFn();
			   }
			  if(payment=='fullpayment')
			   {
			   		$("#installment_plan").hide();
					$("#no_of_installment").hide();
					$(".emi_div").hide();
					$("#down_payment").val($("#total_cost").val());
					$("#down_payment").attr('readonly', true);
					 $("#discount_per").hide();
					discount_rsFn();
			   }
			 
				if(payment){
					$("#discount_rs_container").show();	
				}else{
					$("#discount_rs_container").hide();
				}
			   
			 });

			/*End for Payment section js*/
			
			/*To show other textbox with commission select box*/
			
			
			/*
			$("#custom_commission").hide();
			
			var commission_type=$("#commission").val();
		     if(commission_type=='others')
			   { 
				  $("#custom_commission").show();
			   }
			     
			   if(commission_type!='others')
			   {
				  $("#custom_commission").hide();

			   }
			
			
			$("#commission").live("change",function(){

			   var commission_val=$("#commission").val();

			   if(commission_val=='others')
			   { $("#custom_commission").val("");
				  $("#custom_commission").show();
			   }
			     
			   if(commission_val!='others')
			   {
				  $("#custom_commission").hide();
				  
			   }
			}); 
			
			*/

			/*To show 'Other Textbox' in installment plan*/

			$("#installment_plan").live("change",function(){

			   var installment_plan=$("#installment_plan").val();

			   if(installment_plan=='others')
			   {
				  $("#no_of_installment").show();
			   }
			   if(installment_plan!='others')
			   {
				  $("#no_of_installment").hide();
			   }
			}); 
		  
		});
