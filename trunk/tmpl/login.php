<?php

	if ($err) 
		echo "$err";
	else
		echo "���O�C��";

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
		<input type="checkbox" id="persistent" name="persistent" /><label name="persistent">�T�[�o��email,password��ۑ�����</label>
	</dt>
	</dl>
	<input type="submit" />
</form>

<dl>
<dt class="bold">�v���C�o�V�[�|���V�[</dt>
<dd>
<ul>
	<li>
		'�T�[�o��email/password��ۑ�����'���`�F�b�N���Ȃ�����reblog.ido.nu��email,password���T�[�o�ɕۑ����܂���B���M���ꂽemail,password��tumblr dashboard�Ƀ��O�C�����ăf�[�^�����o�����߂����Ɏg���܂��B
	</li>
	<li>
		���M���ꂽid,password���g���Ď擾����cookie�̓T�[�o�ɕۑ�����܂��B�ł���tumblr�̎d�l�łǂ����ʂ̂Ƃ���Ń��O�C������ƈȑO�ɔ��s���ꂽcookie�͖����ɂȂ�悤�ɂȂ��Ă��܂��B
	</li>
	<li>reblog.ido.nu��reblog���s���Ƃ��̃p�t�H�[�}���X�����P���邽�߂�dashboard�ɕ\�����ꂽ�f�[�^��ۑ����Ă��܂��B���̃f�[�^�͏����̂��ʓ|�Ȃ̂ō��͕ۑ����Ă��܂������̂����P�T�Ԓ��x�ō폜����悤�ɂ��܂��B
	</li>
</ul>
</dd>
<dt class="bold">�Ɛ�</dt>
<dd>
reblog.ido.nu�𗘗p���邱�ƂɊ���Đ����������Ȃ���ɂ��Ă�<a href="http://ido.nu/">ku</a>�͐ӔC�𕉂��܂���B
</dd>
</dl>

