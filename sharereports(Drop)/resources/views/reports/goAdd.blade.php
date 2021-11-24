<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<meta name="author" content="">
		<title>レポート新規作成</title>
		<link rel="stylesheet" href="/css/main.css" type="text/css">
	</head>
	<body>
		<header>
			<h1>レポート新規作成</h1>
			<p>ユーザー名：{{$name}}</p>
			<p><a href="/logout">ログアウト</a></p>
		</header>
		<nav id="breadcrumbs">
			<ul>
				<li><a href="/reports/showList">レポートリスト</a></li>
				<li>レポート新規作成</li>
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
			<form action="/reports/reportAdd" method="post" class="box">
			@csrf
			<label for="newRpDate">
				作業日&nbsp;<span class="required">必須</span>
				<select name="addYear" id="addYear" required>
				<option value="">----</option>
				@for($year = 1970; $year <= 2100; $year++)
					@if($year == $report->getYear())
						<option value="{{$year}}" selected>{{$year}}</option>
					@else
						<option value="{{$year}}">{{$year}}</option>
					@endif
				@endfor
            	</select>
				年
				<select name="addMonth" id="addMonth" required> 
                <option value="">--</option>
                @for($month = 1; $month <= 12; $month++)
					@if($month == $report->getMonth())
						<option value="{{$month}}" selected>{{$month}}</option>
					@else
						<option value="{{$month}}">{{$month}}</option>
					@endif
				@endfor
				</select>
				月
				<select name="addDay" id="addDay" required>
				<option value="">--</option>
				@for($day = 1; $day <= 31; $day++)
					@if($day == $report->getDay())
						<option value="{{$day}}" selected>{{$day}}</option>
					@else
						<option value="{{$day}}">{{$day}}</option>
					@endif
				@endfor
				</select>
				日
			</label><br>
			<label for="newRpTimeFrom">
				作業開始時間&nbsp;<span class="required">必須</span>
				<select name="startHour" id="startHour" required>
				<option value="">--</option>
				@for($hour = 0; $hour <= 24; $hour++)
					@if($hour == $report->getStartHour())
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
					@if($minutes == $report->getstartMinutes())
						<option value="{{$minutes}}" selected>{{$minutes}}</option>
					@else
						<option value="{{$minutes}}">{{$minutes}}</option>
					@endif
				@endfor
				</select>
			</label><br>
			<label for="newRpTimeTo">
				作業終了時間&nbsp;<span class="required">必須</span>
				<select name="lastHour" id="lastHour" required>
				<option value="">--</option>
				@for($hour = 0; $hour <= 24; $hour++)
					@if($hour == $report->getLastHour())
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
					@if($minutes == $report->getLastMinutes())
						<option value="{{$minutes}}" selected>{{$minutes}}</option>
					@else
						<option value="{{$minutes}}">{{$minutes}}</option>
					@endif
				@endfor
				</select>
			</label><br>
			<label for="newRpContent">
				作業内容&nbsp;<span class="required">必須</span>
				<textarea name="newRpContent" id="newRpContent" cols="30" rows="10" value="{{$report->getRpContent()}}" required></textarea>
			</label><br>
			<label for="newReportcateId">
				作業種類ID&nbsp;<span class="required">必須</span>
				<select name="newReportcateId" id="newReportcateId" required>
                <option value="">選択してください</option>
				@foreach($repocateList as $repocateId => $repocte)
					@if($report->getReportcateId() == $repocateId)
						<option value="{{$repocateId}}" selected>{{$repocateId}}:{{$repocte->getRcName()}}</option>
					@else
						<option value="{{$repocateId}}">{{$repocateId}}:{{$repocte->getRcName()}}</option>
					@endif
				@endforeach
				</select>
			</label><br>
			<button type="submit">登録</button>
			</form>
		</section>
	</body>
</html>