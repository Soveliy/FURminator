<?php
	if (isset($_POST['captchaToken'])) {
		$gRecaptchaResponse = $_POST['captchaToken'];
		$secret = "6LdmhtsZAAAAAKe8zwCSdqS_nqgWouH74z7P_XPI";
		$recaptcha = new \ReCaptcha\ReCaptcha($secret);
		$resp = $recaptcha->setExpectedHostname('clients2.evgenykras.ru/FUR/')
			->verify($gRecaptchaResponse/*, $remoteIp*/);
		if ($resp->isSuccess()) {
			$captcha = true;
		} else {
			$errors = $resp->getErrorCodes();
			$captcha = false;
		}
	} else {
		$captcha = false;
	}
	if ( !$captcha )
	{
		$response = ['message' => 'Captcha error'];

		header('Content-type: application/json');
		echo json_encode($response);
		die();
	}


	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	use PHPMailer\PHPMailer\SMTP;

	/*imagine*/
	use Imagine\Image\Box;
	use Imagine\Image\Point;

	require 'phpmailer/src/Exception.php';
	require 'phpmailer/src/PHPMailer.php';
	require __DIR__ . '/vendor/PHPMailer/SMTP.php';
	require 'lib/vendor/autoload.php';

	$mail = new PHPMailer(true);
	$mail->CharSet = 'UTF-8';
	$mail->setLanguage('ru', 'phpmailer/language/');
	$mail->IsHTML(true);
	$mail->Host       = 'smtp.yandex.ru'; // SMTP сервера вашей почты
    $mail->Username   = 'nikita.elagin2012@yandex.ru'; // Логин на почте
    $mail->Password   = '15121997lisaann'; // Пароль на почте
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;

	//От кого письмо
	$mail->setFrom('nikita.elagin2012@yandex.ru', 'furminator.info');
	//Кому отправить
	$mail->addAddress('nikita.elagin2012@yandex.ru');
	//Тема письма
	$mail->Subject = 'furminator.info: Заявка на замену инструмента';

	$body = '<p>Поступила Заявка на замену инструмента с сайта furminator.info. <br>';
	
	if(trim(!empty($_POST['name']))){
		$body.='ФИО отправителя:<strong>'.$_POST['second_name'].' '.$_POST['name'].' '.$_POST['patronymic'].'</strong><br>';
	}
	if(trim(!empty($_POST['phone']))){
		$body.='Контактный телефон:<strong>'.$_POST['phone'].'</strong> <br>';
	}
	if(trim(!empty($_POST['email']))){
		$body.='Контактный e-mail:<strong>'.$_POST['email'].'</strong> <br>';
	}
	if(trim(!empty($_POST['instrument_type']))){
		$body.='<b><br>Информация по инструменту <br><br></b>Тип инструмента:<strong>'.$_POST['instrument_type'].'</strong> <br>';
	}
	if(trim(!empty($_POST['instrument_size']))){
		$body.='Размер инструмента:<strong>'.$_POST['instrument_size'].'</strong> <br>';
	}
	if(trim(!empty($_POST['serial_number']))){
		$body.='Серийный номер:<strong>'.$_POST['serial_number'].'</strong> <br>';
	}
	if(trim(!empty($_POST['coment']))){
		$body.='Причина обращения:'.$_POST['coment'].' <br>';
	}

	if(trim(!empty($_POST['index']))){
		$body.='<b><br>Информация по доставке товара <br><br></b>Индекс:'.$_POST['index'].' <br>';
	}
	if(trim(!empty($_POST['region']))){
		$body.='Регион:'.$_POST['region'].' <br>';
	}
	if(trim(!empty($_POST['city']))){
		$body.='Город:'.$_POST['city'].' <br>';
	}
	if(trim(!empty($_POST['street']))){
		$body.='Улица:'.$_POST['street'].' <br>';
	}
	if(trim(!empty($_POST['home']))){
		$body.='Дом/владение:'.$_POST['home'].' <br>';
	}
	if(trim(!empty($_POST['body']))){
		$body.='Корпус/строение:'.$_POST['body'].' <br>';
	}
	if(trim(!empty($_POST['apartment']))){
		$body.='Квартира:'.$_POST['apartment'].' <br>';
	}
	if(trim(!empty($_POST['dop_info']))){
		$body.='Дополнительная информация:'.$_POST['dop_info'].' <br>';
	}
	// if(trim(!empty($_POST['hand']))){
	// 	$body.='<p><strong>Рука:</strong> '.$hand.'</p>';
	// }
	// if(trim(!empty($_POST['age']))){
	// 	$body.='<p><strong>Возраст:</strong> '.$_POST['age'].'</p>';
	// }
	
	
	
	// Прикрепить файл
	// if (!empty($_FILES['check-file']['tmp_name'])) {
	// 	//путь загрузки файла
	// 	$filePath = __DIR__ . "/files/" . $_FILES['check-file']['name']; 
	// 	//грузим файл
	// 	if (copy($_FILES['check-file']['tmp_name'], $filePath)){
	// 		$fileAttach = $filePath;
	// 		$body.='<p><strong>Фото в приложении</strong>';
	// 		$mail->addAttachment($fileAttach);
	// 	}
	// }

	// for ($ct = 0, $ctMax = count($_FILES['check-file']['tmp_name']); $ct < $ctMax; $ct++) {
    //     $uploadfile = tempnam(sys_get_temp_dir(), hash('sha256', $_FILES['check-file']['name'][$ct]));
    //     $filename = $_FILES['check-file']['name'][$ct];
    //     if (move_uploaded_file($_FILES['check-file']['tmp_name'][$ct], $uploadfile)) {
    //         if (!$mail->addAttachment($uploadfile, $filename)) {
    //             $msg .= 'Failed to attach file ' . $filename;
    //         }
    //     } else {
    //         $msg .= 'Failed to move file to ' . $uploadfile;
	// 	}
		
	// }


	// Set Upload Path
// $target_dir = 'files/';
 
// if( isset($_FILES['check-file']['name'])) {
 
//   $total_files = count($_FILES['check-file']['name']);
 
//   for($key = 0; $key < $total_files; $key++) {
 
//     // Check if file is selected
//     if(isset($_FILES['check-file']['name'][$key]) 
//                       && $_FILES['check-file']['size'][$key] > 0) {
 
//       $original_filename = $_FILES['check-file']['name'][$key];
//       $target = $target_dir . basename($original_filename);
//       $tmp  = $_FILES['check-file']['tmp_name'][$key];
//       move_uploaded_file($tmp, $target);
//     }
 
//   }
 
// }


	

// 	$target_dir = 'files/';
 
// if( isset($_FILES['check-file']['name'])) {
 
//   $total_files = count($_FILES['check-file']['name']);
 
//   for($key = 0; $key < $total_files; $key++) {
 
//     // Check if file is selected
//     if(isset($_FILES['check-file']['name'][$key]) 
//                       && $_FILES['check-file']['size'][$key] > 0) {
 
//       $original_filename = $_FILES['check-file']['name'][$key];
//       $target = $target_dir . basename($original_filename);
//       $tmp  = $_FILES['check-file']['tmp_name'][$key];
//       move_uploaded_file($tmp, $target);
//     }
 
//   }
 
// }



// for($ct=0;$ct<count($_FILES['check-file']['tmp_name']);$ct++){
//     $mail->AddAttachment($_FILES['check-file']['tmp_name'][$ct],$_FILES['check-file']['name'][$ct]);
// }
	function ProcessImageFiles($filesArray, $file_name_suffix='', $img_result_ext='png', $minSize = 2000){
		$result = false;
		try {
			if (!empty($filesArray['name'][0])) {
				$i = 0;
				foreach ($filesArray['name'] as $key => $value) {
					if ( empty($filesArray['tmp_name'][$key]))
					{
						continue; // сообщение об ошибке?
					}
					$imagine = new Imagine\Gd\Imagine();
					$image = $imagine->open($filesArray['tmp_name'][$key]);
					if ( $image ){//если не можем открыть? что делаем? (сообщение?)
						$size      = $image->getSize();
						if ( $size->getWidth() > $minSize && $size->getHeight() > $minSize ){
							if ( $size->getWidth() < $size->getHeight() ){
								$boxHeight = $minSize*$size->getHeight()/$size->getWidth();
								$boxWidth = $minSize;
							} else {
								$boxHeight = $minSize;
								$boxWidth = $minSize*$size->getWidth()/$size->getHeight();
							}
							$image->resize(new Box($boxWidth, $boxHeight));
						}
						$fileExists = true;
						//пока делаем небольшой костыль, чтобы не перезаписывать файлы
						//Оставлять это нельзя, тк при увеличении кол-ва файлов, будет возрастать кол-во обращений к диску
						while ( $fileExists ){
							$i++;
							$fileName = __DIR__.'/warrantyfiles/'.date('Ymd').$file_name_suffix.'-'.$i.'.'.$img_result_ext;
							$fileExists = file_exists($fileName);
						}
						$image->save($fileName);
						unlink($filesArray['tmp_name'][$key]);
						$result[] = array("name" => $filesArray['name'][$key], "tmp_name" => $fileName);
					}

				}
			}
		} catch (Exception $e) {
			$result = false;
		}
		return $result;
	}
	$out_files = ProcessImageFiles($_FILES['check-file'],'_check_file');

	if ($out_files) {
		foreach ($out_files as $k=>$v) {
			$mail->AddAttachment($out_files[$k]['tmp_name'], $out_files[$k]['name']);
		}
	}
	$out_files = ProcessImageFiles($_FILES['photo_file'],'_photo_file');

	if ($out_files) {
		foreach ($out_files as $k=>$v) {
			$mail->AddAttachment($out_files[$k]['tmp_name'], $out_files[$k]['name']);
		}
	}
	$mail->Body = $body;

//Отправляем
	if (!$mail->send()) {
		$message = 'Ошибка';
	} else {
		$message = 'Данные отправлены!';
	}

	$email2 = $_POST['email'];
	$mail2 = new PHPMailer(true);
	$mail2->CharSet = 'UTF-8';
	$mail2->setLanguage('ru', 'phpmailer/language/');
	$mail2->IsHTML(true);

	$mail2->setFrom('nikita.elagin2012@yandex.ru', 'furminator.info');
	//Кому отправить
	$mail2->addAddress($email2);
	//Тема письма

	
	$mail2->Subject = 'furminator.info: Заявка на замену инструмента';

	$body2 = '<p>Спасибо, ваша Заявка на замену инструмента FURminator получена.
	В ближайшее время с вами свяжутся наши специалисты.</p>';
	$mail2->Body = $body2;

	if (!$mail2->send()) {
		$message = 'Ошибка';
	} else {
		$message = 'Данные отправлены!';
	}
	$response = ['message' => $message];

	header('Content-type: application/json');
	echo json_encode($response);
?>
