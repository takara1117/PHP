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
			<p>ユーザー名：{{$name ?? ''}}</p>
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
			
				作業日&nbsp;<span class="required">必須</span>
				<input type="date" name="editRpDate" id="editRpDate" value="{{$report->getRpDate()}}">
			</label><br>
			<label for="editRpTimeFrom">
				作業開始時間&nbsp;<span class="required">必須</span>
				<input type="time" name="editRpTimeFrom" id="editRpTimeFrom" value="{{$report->getRpTimeFrom()}}">
			</label><br>
			<label for="editRpTimeTo">
				作業終了時間&nbsp;<span class="required">必須</span>
				<input type="time" name="editRpTimeTo" id="editRpTimeTo" value="{{$report->getRpTimeTo()}}">
			</label><br>
			<label for="editRpContent">
				作業内容&nbsp;<span class="required">必須</span>
				<textarea name="editRpContent" id="" cols="30" rows="10" required>{{$report->getRpContent()}}</textarea>
			</label><br>
			<label for="editReportcateId">
				作業種類ID&nbsp;<span class="required">必須</span>
				<select name="editReportcateId" id="editReportcateId" required>
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