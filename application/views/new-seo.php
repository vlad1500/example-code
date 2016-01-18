
        <div id="header_edit" class="section-header">
			<div class="row">
				<div class="col-sm-8">
					<h3 class="h4 section--header__title"><?php echo $_COOKIE['book_name']; ?></h3>
				</div>
				<div class="col-sm-4 text-right">					
					<button id="previewer" class="btn btn-small btn-orange">Preview + Publish</button>
					<button id="design-cover" class="btn btn-small btn-orange">Save</button>
				</div>
			</div>
		</div>
<div class="row">
	<div class="col-sm-12">
		<div class="section section--main">

			<form class="form-horizontal" role="form">
				<div class="form-group">
			    	<label class="col-sm-2 control-label">Book Name:</label>
			    	<div class="col-sm-10">
			      		<input type="text" class="form-control" value="The Cookie Book of Wisdom">
			    	</div>
			  	</div>
			  	<div class="form-group">
			    	<label class="col-sm-2 control-label">Authors Name:</label>
			    	<div class="col-sm-10">
			      		<input type="text" class="form-control" value="Stash Harisson">
			    	</div>
			  	</div>
			  	<div class="form-group">
			    	<label class="col-sm-2 control-label">Addition Meta Tags: <br/> <span class="text-sm">(separate phrases with commas)</span></label>
			    	<div class="col-sm-10">
			      		<textarea class="form-control" rows="3"></textarea>
			    	</div>
			  	</div>

			  	<div class="form-group">
			    	<label class="col-sm-2 control-label">Hashtags:</label>
			    	<div class="col-sm-10">
			    		<div class="row">
			    			<div class="col-sm-2">
			    				<input type="text" class="form-control" value="">
			    			</div>
			    			<div class="col-sm-3">
			    				Post to Facebook
			    			</div>
			    			<div class="col-sm-2">
			    				<input type="text" class="form-control" value="">
			    			</div>
			    			<div class="col-sm-3">
			    				Post to Twitter
			    			</div>
			    		</div>
			    	</div>
			  	</div>

			  	<div class="form-group">
			  		<div class="col-sm-2"></div>
			    	<div class="col-sm-10">
			      		<button type="submit" class="btn btn-default btn-orange">Save</button>
			    	</div>
			  	</div>
			</form>

		</div>
	</div>
</div>