var $myjQuery = jQuery.noConflict();
	
	function treeView(){
		$myjQuery('#bulkCategoryList').slideToggle('fast');
		$myjQuery('#treeView').text("");
		
		var content = $myjQuery('#bulkCategoryList');
		var lineArray = content.val().split("\n");
		var index = 0;
		var delimiter = document.getElementById("delimiter").value;
		delimiter == "" ? delimiter = "$" : delimiter = delimiter;
		
		for(var i = 0; i < lineArray.length; i++){
			var categories	= lineArray[i].split("->");
			var preSlug = "";
			for(var j = 0; j < categories.length; j++){
				categories[j] = $myjQuery.trim(categories[j]);
				
				if(categories[j].indexOf(delimiter) != -1){
					var catName = categories[j].split(delimiter)[0];
					var catSlug = categories[j].split(delimiter)[1];
				}else{
					var catName = categories[j];
					var catSlug = categories[j];
				}
				
				if(preSlug == ""&&$myjQuery('li[name="'+catSlug+'"]').length == 0){
                                    //if this is the first time the category show up, put it to the highest level of the tree
					$myjQuery('ul[name="treeView"]').append('<li name="'+catSlug+'">'+catName+'</li>');
				}else if($myjQuery('ul[name="'+preSlug+'"]').length == 0&&$myjQuery('li[name="'+catSlug+'"]').length == 0){
                                    //if this category is the first child of another category, create ul for the parent category before put the child into it
					$myjQuery('li[name="'+preSlug+'"]').append('<a id="a'+index+'" class="link" href="javascript:void(0);" onclick="toggle('+index+');">[+]</a>');
					$myjQuery('li[name="'+preSlug+'"]').append('<div id="'+index+'" style="display:none"><ul name="'+preSlug+'"><li name="'+catSlug+'">'+catName+'</li></ul></div>');
					index++;
				}else if($myjQuery('li[name="'+catSlug+'"]').length == 0){
                                    //look for the right place to put the child category
					$myjQuery('ul[name="'+preSlug+'"]').append('<li name="'+catSlug+'">'+catName+'</li>');
				}
				
				preSlug = catSlug;
			}
		}
		$myjQuery('#preview').fadeOut('fast');
		$myjQuery('#closePreview').fadeIn('fast', function() {
			$myjQuery('#displayTreeView').slideToggle('fast');
		});
	}
	
	function hideTreeView(){
		$myjQuery('#treeView').text("");
		$myjQuery('#displayTreeView').slideToggle('fast');
		$myjQuery('#closePreview').fadeOut('fast', function() { 
			$myjQuery('#preview').fadeIn('fast'); 
		});
		$myjQuery('#bulkCategoryList').slideToggle('fast');
	}
	
	function toggle(id){
		var subcat=document.getElementById(id);
	    var collapse = document.getElementById('a'+id);
  
		if(!subcat||!collapse)return true;
  
		if(subcat.style.display=="none"){
		  $myjQuery('#'+id).slideToggle('fast');
	          collapse.innerHTML = '[-]';
		} else {
		  $myjQuery('#'+id).slideToggle('fast');
		  collapse.innerHTML = '[+]';
		}
	}
	
	function validation(obj){
		if(obj.value == "/"){
			alert("You can not set -> as the delimiter. ");
			obj.value = "";
		}
	}