$(function(){
  $('#subForm').ebcaptcha();
});

(function($){

	jQuery.fn.ebcaptcha = function(options){

		var element = this; 
		var input = this.find('#ebcaptchainput'); 
		var label = this.find('#ebcaptchatext'); 
				$(element).find('input[type=submit]').attr('disabled','disabled'); 

		var randomNr1 = 0; 
		var randomNr2 = 0;
		var totalNr = 0;


		randomNr1 = Math.floor(Math.random()*10);
		randomNr2 = Math.floor(Math.random()*10);
        totalNr = randomNr1 + randomNr2;
        word1 = words[randomNr1];
        word2 = words[randomNr2];
		var texti = `What is ${word1} plus ${word2}?`;
		$(label).text(texti);
		
	
		$(input).keyup(function(){

			var nr = $(this).val();
			if(nr==totalNr)
			{
				$('input[name="hv"]').val("verified");
				$(element).find('input[type=submit]').removeAttr('disabled');				
			}
			else{
				$(element).find('input[type=submit]').attr('disabled','disabled');
			}
			
		});

		$(document).keypress(function(e)
		{
			if(e.which==13)
			{
				if((element).find('input[type=submit]').is(':disabled')==true)
				{
					e.preventDefault();
					return false;
				}
			}

		});

	};

})(jQuery);