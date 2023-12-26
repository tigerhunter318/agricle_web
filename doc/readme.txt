TEST ACCOUNT

==============================================================================================================
ID : minami@lifepd.ecweb.jp
Pass : LIFEpd2022
server : sv13462.xserver.jp 162.43.118.143
/home/xs057239

FTP : info-support@xs057239.xsrv.jp
ID : xs057239
pass : test123123

#既存のpublic_htmlディレクトリを退避
mv /home/xs057239/xs057239.xsrv.jp/public_html /home/xs057239/xs057239.xsrv.jp/_public_html

#シンボリックリンクを作成する
ln -s /home/xs057239/site/public /home/xs057239/xs057239.xsrv.jp/public_html

mySql https://phpmyadmin-sv13462.xserver.jp/?
dbname : xs057239_lifed
name : xs057239_lifed
pass : lifed123

$("#q_details_1").val("{{$selitem[0]['q_details']}}");
			// $("#q_shipping_size_1").val("{{$selitem[0]['q_shipping_size']}}");

			// $("#q_amount_1").val("{{$selitem[0]['q_amount']}}円");
			// $("#q_tax_fee_1").val("{{$selitem[0]['q_tax_fee']}}円");
			// $("#q_user_view_1").val("{{$selitem[0]['q_user_view']}}円");
			// $("#q_amount_fee_1").val("{{$selitem[0]['q_amount_fee']}}円");
			// $("#q_note_fee_1").val("{{$selitem[0]['q_note_fee']}}円");
			// $("#q_user_amount_1").val("{{$selitem[0]['q_user_amount']}}円");

			// $("#order_short_date").val("{{$selitem[0]['order_short_date']}}");
			// $("#order_long_date").val("{{$selitem[0]['order_long_date']}}");
			// $("#schedule_date").val("{{$selitem[0]['schedule_date']}}");
			// $("#cool_price").val("{{$selitem[0]['cool_price']}}");
