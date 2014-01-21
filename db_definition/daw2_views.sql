/*
 * @file: views.sql
 * @author: jequeto@gmail.com
 * @since: 2014 enero
*/

/*
Vista que recuperará todos los permisos de los que disfruta un usuario,
recopilando los asignados directamente en la tabla usuarios_permisos,
y los asignados indirectamente en la tabla usuarios_roles.
*/
create or replace view daw2_v_usuarios_permisos_roles
as
-- de usuarios_permisos
select
		 up.login
		,up.controlador
		,up.metodo
		,null as rol -- rol donante del permiso
from daw2_usuarios_permisos up
union distinct
-- de usuarios_roles
select
		 ur.login
		,rp.controlador
		,rp.metodo
		,ur.rol -- rol donante del permiso
from daw2_usuarios_roles ur inner join daw2_roles_permisos rp on ur.rol=rp.rol
order by login, controlador, metodo, rol
;

/*
Vista que devolverá una relación única de los permisos que tiene asignados
un usuario, sumados los directos más los indirectos (a través de los roles que 
tiene asignados).
*/
create or replace view daw2_v_usuarios_permisos
as
select distinct
		login
		,controlador
		,metodo
from daw2_v_usuarios_permisos_roles
order by login, controlador, metodo
;
