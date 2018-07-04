/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function(){
        $("#submit_button").fadeOut(300);
        
        $(".number").on('keypress',function(e){
        var theEvent = e || window.event;
        var key = theEvent.keyCode || theEvent.which;
        if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46) ){ 
        theEvent.returnValue = false;
        if (theEvent.preventDefault) theEvent.preventDefault();
        }
    })
        $("#reservation").on("keypress",function(e){
            if(e.keyCode==13){
               if($("#reservation").val()=="" && $("#deposit").val()==""){
                    alert("Please put amount to Reservation or Student Deposit")
                    $("#reservation").focus();
                }else{
                computechange();    
                $("#cash_receive").focus();
                }
                e.preventDefault();
            }
        })
        
        $("#deposit").on("keypress",function(e){
            if(e.keyCode==13){
                if($("#reservation").val()=="" && $("#deposit").val()==""){
                    alert("Please put amount to Reservation or Student Deposit")
                    $("#deposit").focus();
                }else{
                $("#cash_receive").focus();
                }
                e.preventDefault();
            }
        })
        
       $("#cash_receive").on("keypress",function(e){
           if(e.keyCode==13){
               if(computechange() < 0){
                    $("#bank").focus();
               }
               e.preventDefault();
            }
           
       }) 
       $("#bank").on("keypress",function(e){
           if(e.keyCode==13){
               $("#check_number").focus()
               e.preventDefault();
           }
       })
       
       $("#check_number").on("keypress",function(e){
           if(e.keyCode==13){
               $("#check_amount").focus()
               e.preventDefault();
           }
       })
       $("#check_amount").on("keypress",function(e){
           if(e.keyCode==13){
               if($("#check_amount").val()==""){
                   alert("Inavlid amount")
               }else{
                   if(computechange()<0){
                     $("#credit_card_bank").focus();  
                   }
               }
               e.preventDefault();
           }
       })
       
       $("#credit_card_bank").on('keypress',function(e){
           if(e.keyCode==13){
           $("#credit_card_type").focus();
            e.preventDefault();
           }
       })
       $("#credit_card_type").on('keypress',function(e){
           if(e.keyCode==13){
           $("#card_number").focus();
            e.preventDefault();
           }
       })
       $("#card_number").on('keypress',function(e){
           if(e.keyCode==13){
           $("#approval_number").focus();
            e.preventDefault();
           }
       })
       $("#approval_number").on('keypress',function(e){
           if(e.keyCode==13){
           $("#credit_card_amount").focus();
            e.preventDefault();
           }
       })
       
       $("#credit_card_amount").on("keypress",function(e){
           if(e.keyCode==13){
               if($("#credit_card_amount").val()==""){
                   alert("Invalid Amount")
               }else{
                   if(computechange()<0){
                     $("#deposit_reference").focus();  
                   }
               }
               e.preventDefault();
           }
       })
       
       $("#deposit_reference").on('keypress',function(e){
           if(e.keyCode==13){
           $("#deposit_amount").focus();
            e.preventDefault();
           }
       })
       $("#deposit_amount").on("keypress",function(e){
           if(e.keyCode==13){
              if(computechange()<0){
                   alert("Invalid Amount");  
                   }
               e.preventDefault();
           }
       })
    })
    
    function computechange(){
         totalamount = 0;
         amountreceive= 0;
         noncash=0;
         if($("#reservation").val()!=""){
            totalamount = totalamount + eval($("#reservation").val())
        }
         if($("#deposit").val()!=""){
            totalamount = totalamount + eval($("#deposit").val());
        }
         if($("#check_amount").val()!=""){
            noncash = noncash + eval($("#check_amount").val())
        }
         if($("#credit_card_amount").val()!=""){
            noncash = noncash + eval($("#credit_card_amount").val())
        }
        if($("#deposit_amount").val()!=""){
            noncash = noncash + eval($("#deposit_amount").val())
        }
         
        if(noncash > totalamount){
             alert("Invalid Amount !!!!!")     
         } else {
             if($("#cash_receive").val()!=""){
                   amountreceive = eval($("#cash_receive").val());
               } 
              if(amountreceive+noncash-totalamount >= 0){
                  $("#submit_button").fadeIn(300)
                  $("#submit_button").focus();
              }else{
                  $("#submit_button").fadeOut(300)
              }
               $("#change").val(amountreceive+noncash-totalamount);
               return amountreceive+noncash-totalamount;
         }
    }