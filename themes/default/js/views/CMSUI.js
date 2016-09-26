/*(function($) {
	$(document).ready(function(){
		
		
		$('#Form_EditForm_ReserveRange').entwine({
			Foreground: 'red',
			Background: 'yellow',
			highlight: function() {
				this.css('background', this.getBackground());
				this.css('color', this.getForeground());
			}
		});
	})
})(jQuery);*/
function cider_cms_func() {
	(function($) {
		$(document).ready(function(){
			function checkReserve(selector) {
				if ($('#'+selector).is(':checked')) {
					$('#Subtitle, #TitleImage, #BorderImage, #See, #Smell, #Taste, #Dryness, #Tannin, #CiderColour, #CrossImageFiles').hide();
					$('#ProductStyle, #ProudctVintage, #ProductSignature').show();
				}else{
					$('#ProductStyle, #ProudctVintage, #ProductSignature').hide();
					$('#Subtitle, #TitleImage, #BorderImage, #See, #Smell, #Taste, #Dryness, #Tannin, #CiderColour, #CrossImageFiles').show();
				}
			}
			
			if ($('#Form_EditForm_ReserveRange').length == 1){
				checkReserve('Form_EditForm_ReserveRange');
				$('#Form_EditForm_ReserveRange').change(function(e) {
					checkReserve('Form_EditForm_ReserveRange');
				});
			}
			
			if ($('#Form_ItemEditForm_ReserveRange').length == 1) {
				checkReserve('Form_ItemEditForm_ReserveRange');
				$('#Form_ItemEditForm_ReserveRange').change(function(e) {
					checkReserve('Form_ItemEditForm_ReserveRange');
				});
			}
			
			$('#Form_ItemEditForm_action_doDelete, #action_doDelete').click(function(e) {
                e.preventDefault();
				
				if (confirm('Are you sure you want to delete this?')) {
					this._super(e);
				}
				
				return false;
            });
			
		})
	})(jQuery);
}