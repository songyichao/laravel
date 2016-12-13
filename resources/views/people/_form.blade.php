	<div class="form-group">
		{{ csrf_field() }}
		<label for="name" class="col-sm-2 control-label">姓名</label>
		
		<div class="col-sm-5">
			<input type="text" name="People[name]"
				   value="{{ old('People')['name'] ?? $people->name }}"
			       class="form-control" id="name" placeholder="请输入学生姓名">
		</div>
		<div class="col-sm-5">
			<p class="form-control-static text-danger">{{ $errors->first('People.name') }}</p>
		</div>
	</div>
	<div class="form-group">
		<label for="age" class="col-sm-2 control-label">年龄</label>
		
		<div class="col-sm-5">
			<input type="text" name="People[age]"
				   value="{{ old('People')['age'] ?? $people->age  }}"
			       class="form-control" id="age" placeholder="请输入学生年龄">
		</div>
		<div class="col-sm-5">
			<p class="form-control-static text-danger">{{ $errors->first('People.age') }}</p>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">性别</label>

		<div class="col-sm-5">
			@foreach($people->sexList() as $key => $info)
			<label class="radio-inline">
				<input type="radio" name="People[sex]"
					   value="{{ $key }}"
						{{ isset($people->sex) && $people->sex == $key ? 'checked' : ''}}> {{ $info }}
			</label>
			@endforeach
		</div>
		<div class="col-sm-5">
			<p class="form-control-static text-danger">{{ $errors->first('People.sex') }}</p>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-primary">提交</button>
		</div>
	</div>
</form>