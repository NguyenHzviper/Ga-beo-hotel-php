<?php include('db_connect.php');?>

<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
			<form action="" id="manage-room">
				<div class="card">
					<div class="card-header">
						    <strong >Trạng Thái Phòng </strong>
				  	</div>
					<div class="card-body">
							<input type="hidden" name="id">
							<div class="form-group">
								<label class="control-label">Phòng</label>
								<input type="text" class="form-control" name="room">
							</div>
							<div class="form-group">
								<label class="control-label">Loại phòng</label>
								<select class="custom-select browser-default" name="category_id">
									<?php 
									$cat = $conn->query("SELECT * FROM room_categories order by name asc ");
									while($row= $cat->fetch_assoc()) {
										$cat_name[$row['id']] = $row['name'];
										?>
										<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
									<?php
									}
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="" class="control-label">Trạng thái</label>
								<select class="custom-select browser-default" name="status">
									<option value="0">Có sẵn</option>
									<option value="1">Không có sẵn</option>

								</select>
							</div>
					</div>
							
					<div class="card-footer">
						<div class="row">
							<div class="col-md-12">
								<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> Lưu</button>
								<button class="btn btn-sm btn-default col-sm-3" type="button" onclick="$('#manage-room').get(0).reset()"> Huỷ bỏ</button>
							</div>
						</div>
					</div>
				</div>
			</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">STT</th>
									<th class="text-center">Loại phòng</th>
									<th class="text-center">Tên phòng</th>
									<th class="text-center">Trạng thái</th>
									<th class="text-center">Tuỳ chỉnh</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$rooms = $conn->query("SELECT * FROM rooms order by id asc");
								while($row=$rooms->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>

								
									<td class="text-center"><?php echo $cat_name[$row['category_id']] ?></td>
									<td class=""><?php echo $row['room'] ?></td>
									<?php if($row['status'] == 0): ?>
										<td class="text-center"><span class="badge badge-success">Có sẵn</span></td>
									<?php else: ?>
										<td class="text-center"><span class="badge badge-default">Không có sẵn</span></td>
									<?php endif; ?>
									<td class="text-center">
										<button class="btn btn-sm btn-primary edit_cat" type="button" data-id="<?php echo $row['id'] ?>" data-room="<?php echo $row['room'] ?>" data-category_id="<?php echo $row['category_id'] ?>" data-status="<?php echo $row['status'] ?>">Sửa</button>
										<button class="btn btn-sm btn-danger delete_cat" type="button" data-id="<?php echo $row['id'] ?>">Xoá</button>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>

<script>
	$('#manage-room').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_room',
			method:"POST",
			data: $(this).serialize(),
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully added",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
				else if(resp==2){
					alert_toast("Data successfully updated",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	})
	$('.edit_cat').click(function(){
		start_load()
		var cat = $('#manage-room')
		cat.get(0).reset()
		cat.find("[name='id']").val($(this).attr('data-id'))
		cat.find("[name='room']").val($(this).attr('data-room'))
		cat.find("[name='category_id']").val($(this).attr('data-category_id'))
		cat.find("[name='status']").val($(this).attr('data-status'))
		end_load()
	})
	$('.delete_cat').click(function(){
		_conf("Are you sure to delete this room?","delete_cat",[$(this).attr('data-id')])
	})
	function delete_cat($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_room',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>