				<form id="quest_access" class="btn-group marg-bottom full-width" data-toggle="buttons">
				
					<label id="set_access_public" class="btn btn-primary <?= $quest->is_public() ? 'active' : null ?>" style="width:50%">
						<input name="access" type="radio" value="public" data-access-type="public" class="" ><i class="icon-unlock icon-large"></i>&nbsp;&nbsp;&nbsp;Public</input>
					</label>
					<label id="set_access_private" class="btn btn-primary <?= $quest->is_private() ? 'active' : null ?>" style="width:50%">
						<input name="access" type="radio" value="private" data-access-type="private" class="" ><i class="icon-lock icon-large"></i>&nbsp;&nbsp;&nbsp;Private</input>
					</label>
					
				</form>