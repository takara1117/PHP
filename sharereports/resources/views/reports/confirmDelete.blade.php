<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<meta name="author" content="">
		<title>レポート削除</title>
		<link rel="stylesheet" href="/css/main.css" type="text/css">
	</head>
	<body>
		<header>
			<h1>レポート削除</h1>
			<p>ユーザー名：{{$name ?? ''}}</p>
		</header>
		<nav id="breadcrumbs">
			<ul>
				<li><a href="/reports/showDetail/{{$report->getId()}}">詳細</a></li>
				<li>レポート削除</li>
			</ul>
		</nav>
		<section>
			<p>以下のレポートを削除します。<br>
			よろしければ、削除ボタンをクリックしてください。
		</p>
		<dl>
			<dt>ID</dt>
			<dd>{{$report->getId()}}</dd>
			<dt>作業日</dt>
			<dd>{{$report->getRpDate()}}</dd>
			<dt>作業開始時間</dt>
			<dd>{{$report->getRpTimeFrom()}}</dd>
			<dt>作業終了時間</dt>
			<dd>{{$report->getRpTimeTo()}}</dd>
			<dt>作業内容</dt>
			<dd>{!! nl2br($report->getRpContent()) !!}</dd>
			<dt>作業種類ID</dt>
			<dd>{{$report->getReportcateId()}}</dd>
		</dl>
		<form action="/reports/reportDelete" method="post">
		@csrf
		<input type="hidden" id="deleteRepoId" name="deleteRepoId" value="{{$report->getId()}}">
		<button type="submit">削除</button>
		</form>
		</section>
	</body>
</html>