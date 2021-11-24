<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<meta name="author" content="">
		<title>レポート内容編集</title>
		<link rel="stylesheet" href="/css/main.css" type="text/css">
	</head>
	<body>
		<header>
			<h1>レポート内容編集</h1>
			<p>ユーザー名：{{$name}}</p>
			<p><a href="/logout">ログアウト</a></p>
		</header>
		<nav id="breadcrumbs">
			<ul>
				<li><a href="/reports/showDetail/{{$report->getId()}}">詳細</a></li>
				<li>レポート内容編集</li>
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
			<p>
				情報を入力し、登録ボタンをクリックしてください。
			</p>
			<form action="/reports/reportEdit" method="post" class="box">
			@csrf
			レポートID:&nbsp;{{$report->getId()}}<br>
			<input type="hidden" name="editRepoId" value="{{$report->getId()}}">
			<label for="editRpDate">
				作業日&nbsp;<span class="required">必須</span>
				<select name="editYear" id="editYear"  required>
				<option value="">----</option>
				@for($year = 1970; $year <= 2100; $year++)
				@if($year == $workDay["year"])
					<option value="{{$year}}" selected>{{$year}}</option>
				@else
					<option value="{{$year}}">{{$year}}</option>
				@endif
				@endfor
            	</select>
				年
				<select name="editMonth" id="editMonth" required> 
                <option value="">--</option>
                @for($month = 1; $month <= 12; $month++)
				@if($month == $workDay["month"])
					<option value="{{$month}}" selected>{{$month}}</option>
				@else
					<option value="{{$month}}">{{$month}}</option>
				@endif	
				@endfor
				</select>
				月
				<select name="editDay" id="editDay" required>
				<option value="">--</option>
				@for($day = 1; $day <= 31; $day++)
				@if($day == $workDay["day"])
					<option value="{{$day}}" selected>{{$day}}</option>
				@else
					<option value="{{$day}}">{{$day}}</option>
				@endif	
				@endfor
				</select>
				日
			</label><br>
			<label for="editRpTimeFrom">
				作業開始時間&nbsp;<span class="required">必須</span>
				<select name="startHour" id="startHour" required>
				<option value="">--</option>
				@for($hour = 0; $hour <= 24; $hour++)
				@if($hour == $startTime["hour"])
					<option value="{{$hour}}" selected>{{$hour}}</option>
				@else
					<option value="{{$hour}}">{{$hour}}</option>
				@endif	
				@endfor
            	</select>
				:
				<select name="startMinutes" id="startMinutes" required> 
                <option value="">--</option>
                @for($minutes = 0; $minutes <= 59; $minutes++)
				@if($minutes == $startTime["minutes"])
					<option value="{{$minutes}}" selected>{{$minutes}}</option>
				@else
					<option value="{{$minutes}}">{{$minutes}}</option>
				@endif	
				@endfor
				</select>
			</label><br>
			<label for="editRpTimeTo">
				作業終了時間&nbsp;<span class="required">必須</span>
				<select name="lastHour" id="lastHour" required>
				<option value="">--</option>
				@for($hour = 0; $hour <= 24; $hour++)
				@if($hour == $lastTime["hour"])
					<option value="{{$hour}}" selected>{{$hour}}</option>
				@else
					<option value="{{$hour}}">{{$hour}}</option>
				@endif	
				@endfor
            	</select>
				:
				<select name="lastMinutes" id="lastMinutes" required> 
                <option value="">--</option>
                @for($minutes = 0; $minutes <= 59; $minutes++)
				@if($minutes == $lastTime["minutes"])
					<option value="{{$minutes}}" selected>{{$minutes}}</option>
				@else
					<option value="{{$minutes}}">{{$minutes}}</option>
				@endif	
				@endfor
				</select>
			</label><br>
			<label for="editRpContent">
				作業内容&nbsp;<span class="required">必須</span>
				<textarea name="editRpContent" id="" cols="30" rows="10" required>{{$report->getRpContent()}}</textarea>
			</label><br>
			<label for="editReportcateId">
				作業種類ID&nbsp;<span class="required">必須</span>
				<select name="editReportcateId" id="editReportcateId" required>
                <option value="">選択してください</option>
				@foreach($repocateList as $repocateId => $repocate)
				@if($report->getReportcateId() == $repocateId)
					<option value="{{$repocateId}}" selected>{{$repocateId}}:{{$repocate->getRcName()}}</option>
				@else
					<option value="{{$repocateId}}">{{$repocateId}}:{{$repocate->getRcName()}}</option>
				@endif
				@endforeach
				</select>
			</label><br>
			<button type="submit">登録</button>
			</form>
		</section>
	</body>
</html>