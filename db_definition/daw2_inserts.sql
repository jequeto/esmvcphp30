

use daw2;

set sql_mode = 'traditional';




insert into daw2_roles
  (rol					, descripcion) values
  ('administradores'	,'Administradores de la aplicación')
, ('usuarios'			,'Todos los usuarios incluido anonimo')
, ('usuarios_logueados'	,'Todos los usuarios excluido anonimo')
;


insert into daw2_usuarios 
  (login, email, password, fecha_alta ,fecha_confirmacion_alta, clave_confirmacion) values
  ('admin', 'admin@email.com', md5('admin00'), default, now(), null)
, ('anonimo', 'anonimo@email.com', md5(''), default, now(), null)
, ('juan', 'juan@email.com', md5('juan00'), default, now(), null)
, ('anais', 'anais@email.com', md5('anais00'), default, now(), null)
;



insert into daw2_recursos
  (controlador,		metodo) values
  ('*'				,'*')
, ('articulos'		,'*')
, ('inicio'			,'index')
, ('usuarios'		,'*')
, ('usuarios'		,'desconectar')
, ('usuarios'		,'form_login')
, ('usuarios'		,'validar_form_login')


;

insert into daw2_roles_permisos
  (rol					,controlador		,metodo) values
  ('administradores'	,'*'				,'*')
, ('usuarios'			,'inicio'			,'index')
, ('usuarios'			,'mensajes'			,'*')
, ('usuarios_logueados','usuarios'			,'desconectar')
, ('usuarios_logueados','inicio'			,'logueado')
;

insert into daw2_usuarios_roles
  (login		,rol) values
  ('admin'		,'administradores')
, ('anonimo'	,'usuarios')
, ('juan'		,'usuarios')
, ('juan'		,'usuarios_logueados')
, ('anais'		,'usuarios')
, ('anais'		,'usuarios_logueados')
;


insert into daw2_usuarios_permisos
  (login			,controlador			,metodo) values
  ('anonimo'		,'usuarios'				,'form_login')
, ('anonimo'		,'usuarios'				,'validar_form_login')
, ('anais'			,'articulos'			,'index')
;


insert into daw2_articulos
  ( categoria_nombre, nombre,precio,unidades_stock ) values
  ('lacteos','leche', 1,500)
, ('lacteos','mantequilla', 0.5, 300)
, ('legumbres', 'arroz', 0.90, 500)
, ('refrescos', 'limonada', 1, 333)
;