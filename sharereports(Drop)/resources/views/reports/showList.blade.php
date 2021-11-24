<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charget="UTF-8">
		<meta name="author" content="">
		<title>レポートリスト</title>
		<link rel="stylesheet" href="/css/main.css" type="text/css">
	</head>
	<body>
		<header>
			<h1>レポートリスト</h1>
			<p>ユーザー名：{{$name ?? ''}}</p>
			<p><a href="/logout">ログアウト</a></p>
		</header>
		@if(session("flashMsg"))
		<section id="flashMsg">
			<p>{{session("flashMsg")}}</p>
		</section>
		@endif
		<section>
			<p>
				レポート新規作成は<a href="/reports/goAdd">こちら</a>から
			</p>
		</section>
		<section>
			<table>
				<thead>
					<tr>
						<th>レポートID</th>
						<th>作業日</th>
						<th>作業開始時間</th>
						<th>作業終了時間</th>
						<th>作業内容</th>
						<th>作業種類ID</th>
						<th>報告者ID</th>
						<th colspan="1">詳細</th>
					</tr>
				</thead>
				<tbody>
					@forelse($reportList as $reportId => $report)
					<tr>
						<td>{{$report->getId()}}</td>
						<td>{{$report->getRpDate()}}</td>
						<td>{{$report->getRpTimeFrom()}}</td>
						<td>{{$report->getRpTimeTo()}}</td>
						<td>{{mb_substr($report->getRpContent(), 0, 10)}}</td>
						<td>{{$report->getReportcateId()}}</td>
						<td>{{$report->getUserId()}}</td>
						<td>
							<a href="/reports/showDetail/{{$report->getId()}}">詳細</a>
						</td>
					</tr>
					@empty
					<tr>
						<td colspan="9">該当レポートは存在しません。</td>
					</tr>
					@endforelse
					
					
					
				
				</tbody>
			</table>
		</section>
	</body>
</html>