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
			<p>ユーザー名：{{$name ?? ''}}</p>
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
				<input type="date" name="newRpDate" id="newRpDate" value="{{$report->getRpDate()}}">
			</label><br>
			<label for="newRpTimeFrom">
				作業開始時間&nbsp;<span class="required">必須</span>
				<input type="time" name="newRpTimeFrom" id="newRpTimeFrom" value="{{$report->getRpTimeFrom()}}">
			</label><br>
			<label for="newRpTimeTo">
				作業終了時間&nbsp;<span class="required">必須</span>
				<input type="time" name="newRpTimeTo" id="newRpTimeTo" value="{{$report->getRpTimeTo()}}">
			</label><br>
			<label for="newRpContent">
				作業内容&nbsp;<span class="required">必須</span>
				<textarea name="newRpContent" id="newRpContent" cols="30" rows="10" value="{{$report->getRpContent()}}" required></textarea>
			</label><br>
			<label for="newReportcateId">
				作業種類ID&nbsp;<span class="required">必須</span>
				<select name="newReportcateId" id="newReportcateId" required>
                <option value="">選択してください</option>
				@foreach($repocateList as $cateId => $repoCte)
				@if($report->getReportcateId() == $cateId)
					<option value="{{$cateId}}" selected>{{$cateId}}:{{$repoCte->getRcName()}}</option>
				@else
					<option value="{{$cateId}}">{{$cateId}}:{{$repoCte->getRcName()}}</option>
				@endif
				@endforeach
				</select>
			</label><br>
			<button type="submit">登録</button>
			</form>
		</section>
	</body>
</html>