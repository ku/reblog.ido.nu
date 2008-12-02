<?php

	if ($err) 
		echo "$err";
	else
		echo "ログイン";

?>
<form action="/login" method="post">
	<dl>
	<dt>
		<label name="email">email</label>
	</dt><dd>
		<input type="text" name="email" id="email" />
	</dd><dt>
		<label name="password">password</label>
	</dt><dd>
		<input type="password" name="password" id="password" />
	</dd>
	<dt>
		<input type="checkbox" id="persistent" name="persistent" /><label name="persistent">サーバにemail,passwordを保存する</label>
	</dt>
	</dl>
	<input type="submit" />
</form>

<dl>
<dt class="bold">プライバシーポリシー</dt>
<dd>
<ul>
	<li>
		'サーバにemail/passwordを保存する'をチェックしない限りreblog.ido.nuはemail,passwordをサーバに保存しません。送信されたemail,passwordはtumblr dashboardにログインしてデータを取り出すためだけに使われます。
	</li>
	<li>
		送信されたid,passwordを使って取得したcookieはサーバに保存されます。ですがtumblrの仕様でどこか別のところでログインすると以前に発行されたcookieは無効になるようになっています。
	</li>
	<li>reblog.ido.nuはreblogを行うときのパフォーマンスを改善するためにdashboardに表示されたデータを保存しています。このデータは消すのが面倒なので今は保存していますがそのうち１週間程度で削除するようにします。
	</li>
</ul>
</dd>
<dt class="bold">免責</dt>
<dd>
reblog.ido.nuを利用することに寄って生じたいかなる問題についても<a href="http://ido.nu/">ku</a>は責任を負いません。
</dd>
</dl>

