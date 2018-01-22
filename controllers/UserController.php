<?php 

class UserController
{
	public function actionRegister()
	{
		
		$name = '';
		$email = '';
		$password = '';

		if (isset($_POST['submit'])) {
			$name = $_POST['name'];
			$email = $_POST['email'];
			$password = $_POST['password'];

			$errors = false;

			if (!User::checkName($name)) {
				$errors[] = 'Имя должно быть не короче 2-х символов';
			}

			if (!User::checkEmail($email)) {
				$errors[] = 'Неправильный email';
			}

			if (!User::checkPassword($password)) {
				$errors[] = 'Пароль должен быть не короче 6-ти символов';
			}

			if (User::checkEmailExists($email)) {
				$errors[] = 'Такой email уже используется';
			}

			if ($errors == false) {
				$result = User::register($name, $email, $password);
			}
		}

		require_once (ROOT.'/views/user/register.php');

		return true;
	}

	public function actionLogin()
	{
		
		$email = '';
		$password = '';

		if (isset($_POST['submit'])) {
			$email = $_POST['email'];
			$password = $_POST['password'];

			$errors = false;

			if (!User::checkEmail($email)) {
				$errors[] = 'Неправильный email';
			}

			if (!User::checkPassword($password)) {
				$errors[] = 'Пароль должен быть не короче 6-ти символов';
			}

			// Проверяем существует ли пользователь
			$userId = User::checkUserData($email, $password);

			if ($userId == false) {
				$errors[] = 'Неправильные данные для входа на сайт';
			} else {
				// Если данные правильные, запоминаем пользователя в сессию
				User::auth($userId);

				// Перенаправляем пользователя в закрытую часть - кабинет
				header("Location: /cabinet/");
			}
		}

		require_once (ROOT.'/views/user/login.php');

		return true;
	}

	public function actionLogout()
	{
		unset($_SESSION["user"]);
		header("Location: /");
	}
}