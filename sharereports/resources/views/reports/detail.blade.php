<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<meta name="author" content="">
		<title>レポート詳細</title>
		<link rel="stylesheet" href="/css/main.css" type="text/css">
	</head>
	<body>
		<header>
			<h1>レポート詳細</h1>
			<p>ユーザー名：{{$name ?? ''}}</p>
		</header>
		<nav id="breadcrumbs">
			<ul>
				<li><a href="/reports/showList">レポートリスト</a></li>
				<li>レポート詳細</li>
			</ul>
		</nav>
		@if(session("flashMsg"))
		<section id="flashMsg">
			<p>{{session("flashMsg")}}</p>
		</section>
		@endif
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
			<table>
				<thead>
					<tr>
						<th>レポートID</th>
						<td>{{$report->getId()}}</td>
					</tr>
					<tr>
					<th>報告者</th>
						@foreach($userList as $userId => $users)
						@if($report->getUserId() == $userId)
						<td>{{$users->getUsName()}}</td>	
						@endif
						@endforeach
					</tr>
					<tr>
					<th>メールアドレス</th>
						@foreach($userList as $userId => $users)
						@if($report->getUserId() == $userId)
						<td>{{$users->getUsMail()}}</td>	
						@endif
						@endforeach
					</tr>
					<tr>
					<th>作業日</th>
						<td>{{$report->getRpDate()}}</td>
					</tr>
					<tr>
					<th>作業開始時間</th>
						<td>{{$report->getRpTimeFrom()}}</td>
					</tr>
					<tr>
					<th>作業終了時間</th>
						<td>{{$report->getRpTimeTo()}}</td>
					</tr>
					<tr>
					<th>作業種類名</th>
						@foreach($repocateList as $cateId => $repoCte)
						@if($report->getReportcateId() == $cateId)
						<td>{{$repoCte->getRcName()}}</td>	
						@endif
						@endforeach
					</tr>
					<tr>
					<th>作業内容</th>
						<td>{!! nl2br($report->getRpContent()) !!}</td>
					</tr>
					<tr>
					<th>レポート登録日時</th>
						<td>{{$report->getRpCreatedAt()}}</td>
					</tr>
					<tr>
					<th>編集</th>
						<td>
							<a href="/reports/prepareEdit/{{$report->getId()}}">編集</a>
						</td>
					</tr>
					<tr>
					<th>削除</th>
						<td>
							<a href="/reports/confirmDelete/{{$report->getId()}}">削除</a>
						</td>
					</tr>
				</thead>
			</table>
		</section>
	</body>
</html>