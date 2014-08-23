(function($) {

	var oProfile = {}

	$(function() {

		$(".add").on("click", function() {
			var oPostit = new Postit();
			oPostit.insertTo($("#container"))
		})

		$(".save").on("click", function() {
				
			$(".postit").each(function() {
				$(this).data("postit").save();
			})

		})

		$(".delete-all").on("click", function() {
			if (confirm("Are you sure you want delete all of them?")) {
				$(".postit").each(function() {
					$(this).data("postit").delete();
				})
			}
		})

		/**
		 * Load all postits
		 */
		$(document).on("process-done", function() {
			Postit.setProfile(oProfile);
			Postit.loadAll($("#container"));
		})

		/**
		 * G+ integration
		 */
		$(document).on("signin", function(evt, authResult) {
			
			if (authResult && !authResult['error']) {

				$("#signinButton").hide();
				$("#signoutButton").show();
				$(document).trigger("load-profile");
		    
	  	} else {
		  	//error callback;
		  	$("#signoutButton").hide();
		  	$("#signinButton").show();
		  }

		}).on("load-profile", function() {

			gapi.client.load('plus','v1', function() {
	    	var request = gapi.client.plus.people.get( {'userId' : 'me'} );

			  request.execute( function(profile) {
					oProfile = profile
					$(document).trigger("process-done")
					//profile["id"]
			  });
	    });

		}).on("signout", function() {
			gapi.auth.signOut();
			oProfile = {}
		})

		$("#signoutButton").on("click", function() {
			$(document).trigger("signout")
		})

	});

})(jQuery);