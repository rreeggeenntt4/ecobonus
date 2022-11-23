			$(document).ready(function(){
            $('#btn_submit').click(function(e){
                // собираем данные с формы
                var b1    = $('#b1').val();
                var s1   = $('#s1').val();
                 var b2    = $('#b2').val();
                var s2   = $('#s2').val();
                 var b3    = $('#b3').val();
                var s3   = $('#s3').val();
                 var b4    = $('#b4').val();
                var s4   = $('#s4').val();
                 var b5    = $('#b5').val();
                var s5   = $('#s5').val();
                 var b6    = $('#b6').val();
                var s6   = $('#s6').val();
                 var b7    = $('#b7').val();
                var s7   = $('#s7').val();
                 var b8    = $('#b8').val();
                var s8   = $('#s8').val();
                 var b9    = $('#b9').val();
                var s9   = $('#s9').val();
                 var b10    = $('#b10').val();
                var s10   = $('#s10').val();
                 var b11    = $('#b11').val();
                var s11   = $('#s11').val();
                 var b12    = $('#b12').val();
                var s12   = $('#s12').val();
                // отправляем данные
                $.ajax({
                    url: "/wp-admin/admin-ajax.php", // куда отправляем
                    type: "post", // метод передачи
                    data: { // что отправляем
                        "b1":    b1,
                        "s1":   s1,
                        "b2":    b2,
                        "s2":   s2,
                        "b3":    b3,
                        "s3":   s3,
                        "b4":    b4,
                        "s4":   s4,
                        "b5":    b5,
                        "s5":   s5,
                        "b6":    b6,
                        "s6":   s6,
                        "b7":    b7,
                        "s7":   s7,
                        "b8":    b8,
                        "s8":   s8,
                        "b9":    b9,
                        "s9":   s9,
                        "b10":    b10,
                        "s10":   s10,
                        "b11":    b11,
                        "s11":   s11,
                        "b12":    b12,
                        "s12":   s12,
                        action: 'eco_bonus'
                    },
                    // после получения ответа сервера
                    	success: function(res){
                        $('.messages').html(res);
                        console.log(res);
                    }
                });
                e.preventDefault();
            });
		});
		
		
		$(document).ready(function(){
            $('#btn_submit2').click(function(e){
                // собираем данные с формы
                var id_nomer  = $('#id_nomer').val();
                var bonus_nomer   = $('#bonus_nomer').val();                
                // отправляем данные
                $.ajax({
                    url: "/wp-admin/admin-ajax.php", // куда отправляем
                    type: "post", // метод передачи
                    data: { // что отправляем
                        "id_nomer":  id_nomer,
                        "bonus_nomer":  bonus_nomer,                        
                        action: 'update_bonus'
                    },
                    // после получения ответа сервера
                    	success: function(res){
                        $('.messages').html(res);
                        console.log(res);
                    }
                });
                e.preventDefault();
            });
		});
