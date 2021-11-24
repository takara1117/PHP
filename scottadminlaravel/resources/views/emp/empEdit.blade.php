<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<meta name="author" content="Shinzo SAITO">
		<title>従業員情報編集</title>
		<link rel="stylesheet" href="/css/main.css" type="text/css">
	</head>
	<body>
		<header>
			<h1>従業員情報編集</h1>
			<p><a href="/logout">ログアウト</a></p>
		</header>
		<nav id="breadcrumbs">
			<ul>
				<li><a href="/goTop">TOP</a></li>
				<li><a href="/emp/showEmpList">従業員リスト</a></li>
				<li>従業員情報編集</li>
			</ul>
		</nav>
		@isset($validationMsgs)
		<section id="errorMsg">
			<p>以下のメッセージをご確認ください。</p>
			<ul>
				@foreach($validationMsgs as $msg)
				<li>{{$msg}}</li>
				@endforeach
			</ul>
		</section>
		@endisset
		<section>
			<p>情報を入力し、更新ボタンをクリックしてください。</p>
			<form action="/emp/empEdit" method="post" class="box">
			@csrf
			従業員ID:&nbsp;{{$emp->getId()}}<br>
			<input type="hidden" name="editEmId" value="{{$emp->getId()}}">
			<label for="editEmNo">
				従業員番号&nbsp;<span class="required">必須</span>
          		<input type="number" min="1000" max="9999" id="editEmNo" name="editEmNo" value="{{$emp->getEmNo()}}" required>
			</label><br>
			<label for="editEmName">
				従業員名&nbsp;<span class="required">必須</span>
				<input type="text" id="editEmName" name="editEmName" value="{{$emp->getEmName()}}" required> 
			</label><br>
			<label for="editEmJob">
				役職&nbsp;<span class="required">必須</span>
				<input type="text" id="editEmJob" name="editEmJob" value="{{$emp->getEmJob()}}" required> 
			</label><br>
			<label for="editEmMgr">
				上司番号&nbsp;<span class="required">必須</span>
				<select type="text" id="editEmMgr" name="editEmMgr" required>
                <option value="" selected>選択してください</option>
                <option value="0">0:上司なし</option>
				@foreach($empList as $mgr => $mgNo)
				@if($emp->getEmMgr() == $mgr)
					<option value="{{$mgr}}" selected>{{$mgr}}:{{$mgNo->getEmName()}}</option>
				@else
					<option value="{{$mgr}}">{{$mgr}}:{{$mgNo->getEmName()}}</option>
				@endif
				@endforeach
            	</select>
			</label><br>
			<label for="editEmHiredate">
				雇用日&nbsp;<span class="required">必須</span>
				<select name="editEmYear" id="editEmYear" required>
                <option value="">----</option>
				@for($year = 1980; $year <= 2021; $year++)
				@if($year == $empDate["year"])
					<option value="{{$year}}" selected>{{$year}}</option>
				@else
					<option value="{{$year}}">{{$year}}</option>
				@endif
				@endfor
            	</select>
				年
				<select name="editEmMonth" id="editEmMonth" required> 
                <option value="">--</option>
                @for($month = 1; $month <= 12; $month++)
				@if($month == $empDate["month"])
					<option value="{{$month}}" selected>{{$month}}</option>
				@else
					<option value="{{$month}}">{{$month}}</option>
				@endif	
				@endfor
				</select>
				月
				<select name="editEmDay" id="editEmDay" required>
				<option value="">--</option>
				@for($day = 1; $day <= 31; $day++)
				@if($day == $empDate["day"])
					<option value="{{$day}}" selected>{{$day}}</option>
				@else
					<option value="{{$day}}">{{$day}}</option>
				@endif	
				@endfor
				</select>
				日
			</label><br>
			<label for="editEmSal">
				給料&nbsp;<span class="required">必須</span>
				<input type="text" id="editEmSal" name="editEmSal" value="{{$emp->getEmSal()}}" required> 
			</label><br>
			<label for="editEmDpId">
				所属部門ID&nbsp;<span class="required">必須</span>
				<select name="editEmDpId" id="editEmDpId" required>
                <option value="">選択してください</option>
				@foreach($deptList as $dpId => $dept)
				@if($emp->getEmDpId() == $dpId)
					<option value="{{$dpId}}" selected>{{$dpId}}:{{$dept->getDpName()}}</option>
				@else
					<option value="{{$dpId}}">{{$dpId}}:{{$dept->getDpName()}}</option>
				@endif
				@endforeach
				</select>
			</label><br>
			<button type="submit">更新</button>
			</form>
		</section>
	</body>
</html>