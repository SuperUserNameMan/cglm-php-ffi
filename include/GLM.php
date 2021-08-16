<?php

/**
 *  The ` GLM ` class is used as a container for the glmc API.
 * 
 * This class only contains static members.
 * 
 * After inclusion, it has to be initialized with ` GLM::GLM(); `.
 * 
 * The full glmc API is bound to ` GLM::$ffi `.
 * 
 * THe ` GLM::__staticCall() ` magic method will automatically callback 
 * the appropriate ` GLM::$ffi->glmc_ ` function each time a non existant 
 * static method of ` GLM:: ` is called.
 * 
 * This means that each glmc function can be called with ` GLM:: `
 * as prefix (instead of ` glmc_ ` ).
 * 
 *  Ex : calling ` GLM::vec3_subadd( $a , $b , $dest ) ` will actually
 * call ` GLM::$ffi->glmc_vec3_subadd( ... ) `.
 * 
 *  Also, this means that it is possible to override each function of the
 * glmc API by adding a static method with the same name into` ` GLM:: `
 * 
 *  Ex : ` GLM::frustum_default() ` is an override method.
 * 
 * Overrides are used to simplify certain aspects of the C API.
 * 
 *  It is also possible to extend the original C API with new static 
 * methods.
 * 
 *  Ex : ` GLM::Vec3() ` can be used to create a new ` vec3 `CData, or 
 * to convert a PHP array into a ` vec3 ` CData.
 * 
 *  These overriding and extending `GLM::` methods are  called "helpers".
 * 
 * 
 *  Following the same principle as the ` GLM:: ` class, many other
 * classes have been added to re-encapsulate some parts of the glmc API.
 * 
 * 
 * Vectors have their own containers :
 * - Vec2:: 
 * - Vec3::
 * - Vec4::
 *  
 * Matrices have their own containers :
 * - Mat2::
 * - Mat3::
 * - Mat4::
 *
 * Aabb has its own container :
 * - Aabb::
 * 
 * 
 * Quaternion (aka "versor") has its own container :
 * - Quat:: 
 * 
 *
 * THe ` ::__staticCall ` magic method of each one of these class will
 * automatically callback the subset of the C API related to each.
 * 
 * Ex : ` Vec2::scale() ` will automatically calback ` glmc_vec2_scale() `.
 *
 * It's like if ` Vec2:: ` replace the prefix ` glmc_vec2_ `.
 * 
 * Also, to make life easier in PHP, each of their ` ::__staticCAll `
 * magic mathod will automatically return the last parameter passed
 * to each ` void ` function.
 * 
 * In most case, this last parameter is a `dest` or `source`  parameter.
 * 
 * With all these tiny improvments, it is thus possible to write :
 * 
 * ````
 * $V = Vec2::scale( Vec2::normalize( GLM::Vec2([ 123 , 456 ]) ), 10.0 );
 * 
 * ````
 * 
 * instead of :
 * 
 * ````
 * $V = FFI::new("float[2]");
 * $D = FFI::new("float[2]");
 * 
 * $V[0] = 123;
 * $V[1] = 456;
 * 
 * Vec2::normalize( $V );
 * Vec2::scale( $V , 10.0 , $D );
 * ````
 * 
 */


class GLM
{

	//----------------------------------------------------------------------------------
	// Const Definition
	//----------------------------------------------------------------------------------

	const E         = 2.71828182845904523536028747135266250  ; /* e           */
	const LOG2E     = 1.44269504088896340735992468100189214  ; /* log2(e)     */
	const LOG10E    = 0.434294481903251827651128918916605082 ; /* log10(e)    */
	const LN2       = 0.693147180559945309417232121458176568 ; /* loge(2)     */
	const LN10      = 2.30258509299404568401799145468436421  ; /* loge(10)    */
	const PI        = 3.14159265358979323846264338327950288  ; /* pi          */
	const PI_2      = 1.57079632679489661923132169163975144  ; /* pi/2        */
	const PI_4      = 0.785398163397448309615660845819875721 ; /* pi/4        */
	const _1_PI     = 0.318309886183790671537767526745028724 ; /* 1/pi        */
	const _2_PI     = 0.636619772367581343075535053490057448 ; /* 2/pi        */
	const _2_SQRTPI = 1.12837916709551257389615890312154517  ; /* 2/sqrt(pi)  */
	const SQRT2     = 1.41421356237309504880168872420969808  ; /* sqrt(2)     */
	const SQRT1_2   = 0.707106781186547524400844362104849039 ; /* 1/sqrt(2)   */

	// --- FrustumCorners->v[ index ] :

	const LBN = 0 ; /* left  bottom near */
	const LTN = 1 ; /* left  top    near */
	const RTN = 2 ; /* right top    near */
	const RBN = 3 ; /* right bottom near */

	const LBF = 4 ; /* left  bottom far  */
	const LTF = 5 ; /* left  top    far  */
	const RTF = 6 ; /* right top    far  */
	const RBF = 7 ; /* right bottom far  */

	// --- FrustumPlanes->v[ index ]
	const LEFT   = 0 ;
	const RIGHT  = 1 ;
	const BOTTOM = 2 ;
	const TOP    = 3 ;
	const NEAR   = 4 ;
	const FAR    = 5 ;


	static $typeof_void_p; // TODO renom GLM::$typeof_xxx and GLM::$ffi_typeof_xxx

	static $typeof_vec2;
	static $typeof_vec3;
	static $typeof_vec4;
	
	static $typeof_mat2;
	static $typeof_mat3;
	static $typeof_mat4;
	
	static $typeof_aabb;
	
	static $typeof_quat;

	//----------------------------------------------------------------------------------
	// FFI initialisation
	//----------------------------------------------------------------------------------

	public static $ffi;

	public static function GLM()
	{
		$cdef = __DIR__ . '/GLM.ffi.php.h';
		static::$ffi = FFI::load($cdef);
		
		static::$typeof_void_p= static::$ffi->type( "void*"  ); // TODO FIXME HACK : find a better way to FFI::type("void");

		static::$typeof_vec2  = static::$ffi->type( "vec2"   );
		static::$typeof_vec3  = static::$ffi->type( "vec3"   );
		static::$typeof_vec4  = static::$ffi->type( "vec4"   );
		
		static::$typeof_mat2  = static::$ffi->type( "mat2"   );
		static::$typeof_mat3  = static::$ffi->type( "mat3"   );
		static::$typeof_mat4  = static::$ffi->type( "mat4"   );
		
		static::$typeof_aabb  = static::$ffi->type( "Aabb");
		
		static::$typeof_quat  = static::$ffi->type( "versor" ); //!\ "versor" is how cglm calls "quaternion"
	}


	public static function __callStatic( $method , $args )
	{
		$callable = [static::$ffi, 'glmc_'.$method];
		return $callable(...$args);
	}
	
	//----------------------------------------------------------------------------------
	// Helpers
	//----------------------------------------------------------------------------------
	
	public static function ffi_returned_void( $ffi_ret )
	{
		return 
			is_object( $ffi_ret )
			&&
			get_class( $ffi_ret ) == "FFI\CData"
			&&
			FFI::typeof( FFI::addr( $ffi_ret ) ) == static::$typeof_void_p // TODO HACK FIXME : with PHP8.8 I can't find a way to test directly against FFI::type("void");
			;
	}
	
	
	public static function Vec2( $V ) // TODO renommer en GLM::Vec2($V) and GLM::to_Vec2( $V ) puis utilise GLM::Vec2($A=null) comme alias de Vec2::new($A=null)
	{
		if ( static::is_Vec2( $V ) ) return $V ;
		
		return Vec2::new([ $V[0] , $V[1] ]);
	}
	
	public static function Vec3( $V , $_z=0.0 ) // TODO renommer en to_Vec3( $V )
	{
		if ( static::is_Vec3( $V ) ) return $V ;
		
		return Vec3::new([ $V[0] , $V[1] , $V[2] ?? $_z ?? 0.0 ]);
	}
	
	public static function Vec4( $V , $_w=1.0 )
	{
		if ( static::is_Vec4( $V ) ) return $V ;
		
		return Vec4::new([ $V[0] , $V[1] , $V[2] ?? 0.0 , $V[3] ?? $_w ?? 1.0 ]);
	}
	
	public static function Mat2( $M )
	{
		if ( static::is_Mat2( $M ) ) return $M ;
		
		return Mat2::new( $M );
	}
	
	public static function Mat3( $M )
	{
		if ( static::is_Mat3( $M ) ) return $M ;
		
		return Mat3::new( $M );
	}
	
	public static function Mat4( $M )
	{
		if ( static::is_Mat4( $M ) ) return $M ;
		
		return Mat4::new( $M );
	}
	
	public static function Aabb( $B )
	{
		if ( static::is_Aabb( $B ) ) return $B ;
		
		return Aabb::new( $B );
	}
	
	public static function Quat( $Q )
	{
		if ( static::is_Quat( $Q ) ) return $Q ;
		
		return Quat::new( $Q );
	}
	
	public static function is_Vec2( $V )
	{
		return
			is_object( $V )
			&&
			get_class( $V ) == "FFI\CData"
			&&
			FFI::typeof( $V ) == static::$typeof_vec2
			;
	}
	
	public static function is_Vec3( $V )
	{
		return
			is_object( $V )
			&&
			get_class( $V ) == "FFI\CData"
			&&
			FFI::typeof( $V ) == static::$typeof_vec3
			;
	}
	
	public static function is_Vec4( $V )
	{
		return
			is_object( $V )
			&&
			get_class( $V ) == "FFI\CData"
			&&
			FFI::typeof( $V ) == static::$typeof_vec4
			;
	}
	
	public static function is_Mat2( $V )
	{
		return
			is_object( $V )
			&&
			get_class( $V ) == "FFI\CData"
			&&
			FFI::typeof( $V ) == static::$typeof_mat2
			;
	}
	
	public static function is_Mat3( $V )
	{
		return
			is_object( $V )
			&&
			get_class( $V ) == "FFI\CData"
			&&
			FFI::typeof( $V ) == static::$typeof_mat3
			;
	}
	
	public static function is_Mat4( $V )
	{
		return
			is_object( $V )
			&&
			get_class( $V ) == "FFI\CData"
			&&
			FFI::typeof( $V ) == static::$typeof_mat4
			;
	}
	
	public static function is_Aabb( $B )
	{
		return
			is_object( $B )
			&&
			get_class( $B ) == "FFI\CData"
			&&
			FFI::typeof( $B ) == static::$typeof_aabb
			;
	}
	
	public static function is_Quat( $Q )
	{
		return
			is_object( $Q )
			&&
			get_class( $Q ) == "FFI\CData"
			&&
			FFI::typeof( $Q ) == static::$typeof_quat
			;
	}
	
	
	// affine.h --------------------------------
	
	public static function translate_make( $M , $V )
	{
		$M = static::Mat4( $M );
		$V = static::Vec3( $V );
		
		static::$ffi->glmc_translate_make( $M , $V );
		
		return $M;
	}
	
	public static function translate_to( $M , $V , $D )
	{
		$V = static::Vec3( $V );
		
		static::$ffi->glmc_translate_to( $M , $V , $D );
		
		return $D;
	}

	public static function translate( $M , $V )
	{
		$V = static::Vec3( $V );
		
		static::$ffi->glmc_translate( $M , $V );
		
		return $M;
	}

	public static function translate_x( $M , float $x )
	{	
		static::$ffi->glmc_translate_x( $M , $x );
		
		return $M;
	}
	
	public static function translate_y( $M , float $y )
	{	
		static::$ffi->glmc_translate_y( $M , $y );
		
		return $M;
	}
	
	public static function translate_z( $M , float $z )
	{	
		static::$ffi->glmc_translate_x( $M , $z );
		
		return $M;
	}
	
	
	public static function scale_make( $M , $V )
	{
		$M = static::Mat4( $M );		
		$V = static::Vec3( $V );
		
		static::$ffi->glmc_scale_make( $M , $V );
		
		return $M;
	}
	
	public static function scale_to( $M , $V , $D )
	{
		$V = static::Vec3( $V );
		
		static::$ffi->glmc_scale_to( $M , $V , $D );
		
		return $D;
	}

	public static function scale( $M , $V )
	{
		$V = static::Vec3( $V );
		
		static::$ffi->glmc_scale( $M , $V );
		
		return $M;
	}

	public static function scale_uni( $M , float $s )
	{	
		static::$ffi->glmc_scale_uni( $M , $s );
		
		return $M;
	}
	
	public static function rotate_x( $M , float $rad , $D = null )
	{
		$D = static::Mat4( $D );
		
		static::$ffi->glmc_rotate_x( $M, $rad, $D );
		
		return $D;
	}
	
	public static function rotate_y( $M , float $rad , $D = null )
	{
		$D = static::Mat4( $D );
		
		static::$ffi->glmc_rotate_y( $M, $rad, $D );
		
		return $D;
	}
	
	public static function rotate_z( $M , float $rad , $D = null )
	{
		$D = static::Mat4( $D );
		
		static::$ffi->glmc_rotate_z( $M, $rad, $D );
		
		return $D;
	}

	public static function rotate_make( $M , float $angle , $V_axis )
	{
		$M = static::Mat4( $M );
		$V_axis = static::Vec3( $V_axis );
		
		static::$ffi->glmc_rotate_make( $M , $angle, $V_axis );
		
		return $M;
	}
	
	public static function rotate( $M , float $angle , $V_axis )
	{
		$V_axis = static::Vec3( $V_axis );
		
		static::$ffi->glmc_rotate( $M , $angle, $V_axis );
		
		return $M;
	}

	public static function rotate_at( $M , $V_pivot , float $angle , $V_axis )
	{
		$V_pivot = static::Vec3( $V_pivot );
		$V_axis  = static::Vec3( $V_axis  );
	
		static::$ffi->glmc_rotate_at( $M , $V_pivot , $angle , $V_axis );
		
		return $M;
	}
	
	public static function decompose_scalev( $M , $V = null )
	{
		$V = static::Vec3( $V );
		
		static::$ffi->glmc_decompose_scalev( $M , $V );
		
		return $V;
	}
	
	public static function decompose_rs( $M , $R = null , $V = null )
	{
		$R = static::Mat4( $R );
		$V = static::Vec3( $V );
		
		static::$ffi->glmc_decompose_rs( $M , $R , $V );
		
		return [ $R , $V ]; //!\\
	}

	public static function decompose( $M , $V_t=null , $R=null , $V_s=null )
	{
		$V_t = static::Vec3( $V_t );
		$V_s = static::Vec3( $V_s );
		$R   = static::Mat4( $R   );
		
		static::$ffi->glmc_decompose( $M , $V_t , $R , $V_s );
		
		return [ $V_t , $R , $V_s ];
	}
	
	public static function mul( $M1 , $M2 , $D=null )
	{
		$D = static::Mat4( $D );
		
		static::$ffi->glmc_mul( $M1 , $M2 , $D );
		
		return $D;
	}
	
	public static function mul_rot( $M1 , $M2 , $D=null )
	{
		$D = static::Mat4( $D );
		
		static::$ffi->glmc_mul_rot( $M1 , $M2 , $D );
		
		return $D;
	}
	
	public static function inv_tr( $M )
	{
		static::$ffi->glmc_inv_tr( $M );
		
		return $M;
	}
	
	// cam.h -----------------------------------------------------------
	
	public static function frustum( float $left , float $right , float $bottom , float $top , float $nearZ , float $farZ , $D=null )
	{
		$D = static::Mat4( $D );
		
		static::$ffi->glmc_frustum( $left , $right , $bottom , $top , $nearZ , $farZ , $D );
		
		return $D;
	}
	
	public static function ortho( float $left , float $right , float $bottom , float $top , float $nearZ , float $farZ , $D=null )
	{
		$D = static::Mat4( $D );
		
		static::$ffi->glmc_ortho( $left , $right , $bottom , $top , $nearZ , $farZ , $D );
		
		return $D;
	}
	
	public static function ortho_aabb( $B , $D=null )
	{
		$B = static::Aabb( $B );
		$D = static::Mat4( $D );
		
		static::$ffi->glmc_ortho_aabb( $B->v , $D );
		
		return $D;
	}
	
	public static function ortho_aabb_p( $B , float $padding , $D=null )
	{
		$B = static::Aabb( $B );
		$D = static::Mat4( $D );
		
		static::$ffi->glmc_ortho_aabb_p( $B->v , $padding , $D );
		
		return $D;
	}
	
	public static function ortho_aabb_pz( $B , float $padding , $D=null )
	{
		$B = static::Aabb( $B );
		$D = static::Mat4( $D );
		
		static::$ffi->glmc_ortho_aabb_pz( $B->v , $padding , $D );
		
		return $D;
	}
	
	public static function ortho_default( float $aspect , $D=null )
	{
		$D = static::Mat4( $D );
		
		static::$ffi->glmc_ortho_default( $aspect , $D );
		
		return $D;
	}
	
	public static function ortho_default_s( float $aspect , float $size , $D=null )
	{
		$D = static::Mat4( $D );
		
		static::$ffi->glmc_ortho_default_s( $aspect , $size , $D );
		
		return $D;
	}
	
	public static function perspective( float $fovy , float $aspect , float $nearZ , float $farZ , $D=null )
	{
		$D = static::Mat4( $D );
		
		static::$ffi->glmc_perspective( $fovy , $aspect , $nearZ , $farZ , $D );
		
		return $D;
	}
	
	public static function persp_move_far( $P , float $deltaFar )
	{
		$P = static::Mat4( $P );
		
		static::$ffi->glmc_persp_move_far( $P , deltaFar );
		
		return $P;
	}
	
	public static function perspective_default( float $aspect , $D=null )
	{
		$D = static::Mat4( $D );
		
		static::$ffi->glmc_perspective_default( $aspect , $D );
		
		return $D;
	}
	
	public static function perspective_resize( $P , float $aspect )
	{
		$P = static::Mat4( $P );
		
		static::$ffi->glmc_perspective_resize( $aspect , $P ); //!\ C params inverted
		
		return $P;
	}
	
	public static function lookat( $V_eye , $V_center , $V_up , $D=null )
	{
		$V_eye    = static::Vec3( $V_eye    );
		$V_center = static::Vec3( $V_center );
		$V_up     = static::Vec3( $V_up     );
		
		$D = static::Mat4( $D );
		
		static::$ffi->glmc_lookat( $V_eye , $V_center , $V_up , $D );
		
		return $D;
	}
	
	public static function look( $V_eye , $V_dir , $V_up , $D=null )
	{
		$V_eye = static::Vec3( $V_eye );
		$V_dir = static::Vec3( $V_dir );
		$V_up  = static::Vec3( $V_up  );
		
		$D = static::Mat4( $D );
		
		static::$ffi->glmc_look( $V_eye , $V_dir , $V_up , $D );
		
		return $D;
	}
	
	public static function look_anyup( $V_eye , $V_dir , $D=null )
	{
		$V_eye = static::Vec3( $V_eye );
		$V_dir = static::Vec3( $V_dir );
		
		$D = static::Mat4( $D );
		
		static::$ffi->glmc_look_anyup( $V_eye , $V_dir , $D );
		
		return $D;
	}
	
	public static function persp_decomp( $P )
	{
		$f = static::$ffi->new("FrustumPlanes");
		
		static::$ffi->glmc_persp_decomp( $P 
			, FFI::addr( $f->near   )
			, FFI::addr( $f->far    )
			, FFI::addr( $f->top    )
			, FFI::addr( $f->bottom ) 
			, FFI::addr( $f->left   )
			, FFI::addr( $f->right  )
		);
		
		return $f;
	}
	
	public static function persp_decomp_x( $P )
	{
		$f = static::$ffi->new("struct{ float left, right; }");
		
		static::$ffi->glmc_persp_decomp_x( $P 
			, FFI::addr( $f->left   )
			, FFI::addr( $f->right  )
		);
		
		return $f;
	}
	
	public static function persp_decomp_y( $P )
	{
		$f = static::$ffi->new("struct{ float top, bottom; }");
		
		static::$ffi->glmc_persp_decomp_y( $P 
			, FFI::addr( $f->top    )
			, FFI::addr( $f->bottom )
		);
		
		return $f;
	}
	
	public static function persp_decomp_z( $P )
	{
		$f = static::$ffi->new("struct{ float near, far; }");
		
		static::$ffi->glmc_persp_decomp_z( $P 
			, FFI::addr( $f->near )
			, FFI::addr( $f->far  )
		);
		
		return $f;
	}
	
	public static function persp_decomp_far( $P )
	{
		$f = static::$ffi->new("float[1]");
		
		static::$ffi->glmc_persp_decomp_far( $P , FFI::addr( $f[0] ) );
		
		return $f[0];
	}
	
	public static function persp_decomp_near( $P )
	{
		$f = static::$ffi->new("float[1]");
		
		static::$ffi->glmc_persp_decomp_near( $P , FFI::addr( $f[0] ) );
		
		return $f[0];
	}
	
	public static function persp_sizes( $P , float $fovy , $D=null )
	{
		$D = static::Vec4( $D );
		
		static::$ffi->glmc_persp_sizes( $P , $fovy , $D );
		
		return $D;
	}
	
	// euler.h ---------------------------------------------------------
	
	public static function euler_angles( $M , $V )
	{
		$V = static::Vec3( $V );
		
		static::$ffi->glmc_euler_angles( $M , $V );
		
		return $V;
	}
	
	public static function euler( $V , $M )
	{
		$M = static::Mat4( $M );
		
		static::$ffi->glmc_euler( $V , $M );
		
		return $M;
	}
	
	public static function euler_xyz( $V , $M )
	{
		$M = static::Mat4( $M );	
		static::$ffi->glmc_euler_xyz( $V , $M );
		return $M;
	}
	
	public static function euler_zyx( $V , $M )
	{
		$M = static::Mat4( $M );
		static::$ffi->glmc_euler_zyx( $V , $M );
		return $M;
	}
	
	public static function euler_zxy( $V , $M )
	{
		$M = static::Mat4( $M );	
		static::$ffi->glmc_euler_zxy( $V , $M );
		return $M;
	}
	
	public static function euler_xzy( $V , $M )
	{
		$M = static::Mat4( $M );	
		static::$ffi->glmc_euler_xzy( $V , $M );
		return $M;
	}
	
	public static function euler_yzx( $V , $M )
	{
		$M = static::Mat4( $M );	
		static::$ffi->glmc_euler_yzx( $V , $M );
		return $M;
	}
	
	public static function euler_yxz( $V , $M )
	{
		$M = static::Mat4( $M );	
		static::$ffi->glmc_euler_yxz( $V , $M );
		return $M;
	}
	
	
	// frustum.h -------------------------------------------------------
	
	public static function FrustumPlanes( /*TODO add init params */ )
	{
		return static::$ffi->new("FrustumPlanes");
	}
	public static function FrustumCorners( /*TODO add init params */ )
	{
		return static::$ffi->new("FrustumCorners");
	}
	public static function PlaneCorners( /*TODO add init params */ )
	{
		return static::$ffi->new("PlaneCorners");
	}
	
	public static function frustum_planes( $M , $Planes=null )
	{
		if ( $Planes === null )
		{
			$Planes = static::FrustumPlanes();
		}
		
		static::$ffi->glmc_frustum_planes( $M , $Planes->v );
		
		return $Planes;
	}
	
	public static function frustum_corners( $M , $Corners=null )
	{
		if ( $Corners === null )
		{
			$Corners = static::FrustumCorners();
		}
		
		static::$ffi->glmc_frustum_corners( $M , $Corners->v );
		
		return $Corners;
	}
	
	public static function frustum_center( $Corners , $V_dest=null )
	{
		$V_dest = static::Vec4( $V_dest );
		
		static::$ffi->glmc_frustum_center( $Corners->v , $V_dest );
		
		return $V_dest;
	}
	
	public static function frustum_box( $Corners , $M , $Aabb )
	{
		$Aabb = GLM::Aabb( $Aabb );
		
		static::$ffi->glmc_frustum_box( $Corners->v , $M , $Aabb->v );
		
		return $Aabb;
	}
	
	public static function frustum_corners_at( $Corners_f , float $splitDist , float $farDist , $Corners_p=null )
	{
		if ( $Corners_p === null )
		{
			$Corners_p = GLM::PlaneCorners();
		}
		
		static::$ffi->glmc_frustum_corners_at( $Corners_f->v , $splitDist , $farDist , $Corners_p->v );
		
		return $Corners_p ;
	}
}

class Vec2
{
	public static function __callStatic( $method , $args )
	{
		$callable = [GLM::$ffi, 'glmc_vec2_'.$method];
		
		$res = $callable(...$args);
		
		//return $res;
		
		if ( ! GLM::ffi_returned_void( $res ) ) return $res;
		
		// Most time, the last parameter is a destination vector,
		// so we can return it instead of void.
		// The only exception is glmc_vec2_clamp() which will be
		// overriden below. 
		
		return $args[ count( $args ) -1 ]; 
	}
	
	public static function new( $A = null )
	{
		$V = GLM::$ffi->new("vec2");
		
		if ( ! empty( $A ) )
		{
			$V[0] = $A[0] ?? 0.0 ;
			$V[1] = $A[1] ?? 0.0 ;
		}
		
		return $V;
	}
	
	public static function clone( $A )
	{
		$D = GLM::$ffi->new("vec2");
		GLM::$ffi->glmc_vec2_copy( $A , $D );
		return $D;
	}
	
	public static function clamp( $V , float $minval , float $maxval )
	{
		// See __callStatic for the reason why it is overriden.
		
		GLM::$ffi->glmc_vec2_clamp( $V , $minval , $maxval );
		return $V;
	}
	
}

class Vec3
{
	public static function __callStatic( $method , $args )
	{
		$callable = [GLM::$ffi, 'glmc_vec3_'.$method];
		
		$res = $callable(...$args);
		
		//return $res;
		
		if ( ! GLM::ffi_returned_void( $res ) ) return $res;
		
		// Most time, the last parameter is a destination vector,
		// so we can return it instead of void.
		// The exceptions are :
		// - glmc_vec3_rotate( v , angle , axis )
		// - glmc_vec3_clamp( v , min, max )
		// - glmc_vec3_fill( v , val )
		// They will be overriden below. 
		
		return $args[ count( $args ) -1 ]; 
	}
	
	public static function new( $A = null )
	{
		$V = GLM::$ffi->new("vec3");
		
		if ( ! empty( $A ) )
		{
			$V[0] = $A[0] ?? 0.0 ;
			$V[1] = $A[1] ?? 0.0 ;
			$V[2] = $A[2] ?? 0.0 ;
		}
		
		return $V;
	}
	
	public static function clone( $A )
	{
		$D = GLM::$ffi->new("vec3");
		GLM::$ffi->glmc_vec3_copy( $A , $D );
		return $D;
	}
	
	public static function clamp( $V , float $minval , float $maxval )
	{
		// See __callStatic for the reason why it is overriden.
		
		GLM::$ffi->glmc_vec3_clamp( $V , $minval , $maxval );
		
		return $V;
	}
	
	public static function rotate( $V , float $angle , $V_axis )
	{
		// See __callStatic for the reason why it is overriden.
		
		GLM::$ffi->glmc_vec3_rotate( $V , $angle , $V_axis );
		
		return $V;
	}
	
	public static function fill( $V , float $val )
	{
		// See __callStatic for the reason why it is overriden.
		
		GLM::$ffi->glmc_vec3_fill( $V , $val );
		
		return $V;
	}
	
	
	//CGLM_INLINE
	public static function mix( $V_from , $V_to , float $t , $V_dest )
	{
		GLM::$ffi->glmc_vec3_lerp( $V_from , $V_to , $t , $V_dest );
		
		return $V_dest;
	}
	
	//CGLM_INLINE
	public static function mixc( $V_from , $V_to , float $t , $V_dest )
	{
		GLM::$ffi->glmc_vec3_lerpc( $V_from , $V_to , $t , $V_dest );
		
		return $V_dest;
	}
}


class Vec4
{
	public static function __callStatic( $method , $args )
	{
		$callable = [GLM::$ffi, 'glmc_vec4_'.$method];
		
		$res = $callable(...$args);
		
		//return $res;
		
		if ( ! GLM::ffi_returned_void( $res ) ) return $res;
		
		// Most time, the last parameter is a destination vector,
		// so we can return it instead of void.
		// The exceptions are :
		// - glmc_vec4_clamp( v , min, max )
		// - glmc_vec4_fill( v , val )
		// They will be overriden below. 
		
		return $args[ count( $args ) -1 ]; 
	}
	
	public static function new( $A = null )
	{
		$V = GLM::$ffi->new("vec4");
		
		if ( ! empty( $A ) )
		{
			$V[0] = $A[0] ?? 0.0 ;
			$V[1] = $A[1] ?? 0.0 ;
			$V[2] = $A[2] ?? 0.0 ;
			$V[3] = $A[3] ?? 1.0 ; // ONE
		}
		
		return $V;
	}
	
	public static function clone( $A )
	{
		$D = GLM::$ffi->new("vec4");
		GLM::$ffi->glmc_vec4_copy( $A , $D );
		return $D;
	}
	
	public static function clamp( $V , float $minval , float $maxval )
	{
		// See __callStatic for the reason why it is overriden.
		
		GLM::$ffi->glmc_vec4_clamp( $V , $minval , $maxval );
		
		return $V;
	}
	
	public static function fill( $V , float $val )
	{
		// See __callStatic for the reason why it is overriden.
		
		GLM::$ffi->glmc_vec4_fill( $V , $val );
		
		return $V;
	}
	
	//CGLM_INLINE
	public static function mix( $V_from , $V_to , float $t , $V_dest )
	{
		GLM::$ffi->glmc_vec4_lerp( $V_from , $V_to , $t , $V_dest );
		
		return $V_dest;
	}
	
	//CGLM_INLINE
	public static function mixc( $V_from , $V_to , float $t , $V_dest )
	{
		GLM::$ffi->glmc_vec4_lerpc( $V_from , $V_to , $t , $V_dest );
		
		return $V_dest;
	}
	
	// plane.h ---------------------------------------------------------
	
	public static function plane_normalize( $V )
	{
		GLM::$ffi->glmc_plane_normalize( $V );
		return $V;
	}
}



class Mat2
{
	public static function __callStatic( $method , $args )
	{
		$callable = [GLM::$ffi, 'glmc_mat2_'.$method];
		
		$res = $callable(...$args);
		
		//return $res;
		
		if ( ! GLM::ffi_returned_void( $res ) ) return $res;
		
		// Most time, the last parameter is a destination vector,
		// so we can return it instead of void.
		// The exceptions are :
		// - glmc_mat2_scale( m , s )
		// - glmc_mat2_swap_col(mat2 mat, int col1, int col2);
		// - glmc_mat2_swap_row(mat2 mat, int row1, int row2);
		// They will be overriden below. 
		
		return $args[ count( $args ) -1 ]; 
	}
	
	public static function new( $A = null )
	{
		$M = GLM::$ffi->new("mat2");
		
		if ( ! empty( $A ) )
		{
			$M[0][0] = $A[0][0] ?? $A[0] ?? 0.0 ;
			$M[0][1] = $A[0][1] ?? $A[1] ?? 0.0 ;
			
			$M[1][0] = $A[1][0] ?? $A[2] ?? 0.0 ;
			$M[1][1] = $A[1][1] ?? $A[3] ?? 0.0 ;
		}
		
		return $M;
	}
	
	public static function clone( $A )
	{
		$D = GLM::$ffi->new("mat2");
		GLM::$ffi->glmc_mat2_copy( $A , $D );
		return $D;
	}
	
	public static function scale( $M , float $s )
	{
		GLM::$ffi->glmc_mat2_scale( $M , $s );
		
		return $M;
	}
	
	public static function swap_col( $M , int $col1, int $col2 )
	{
		GLM::$ffi->glmc_mat2_swap_col( $M , $col1 , $col2 );
		
		return $M;
	}
	
	public static function swap_row( $M, int $row1 , int $row2 )
	{
		GLM::$ffi->glmc_mat2_swap_row( $M , $row1 , $row2 );
		
		return $M;
	}
}


class Mat3
{
	public static function __callStatic( $method , $args )
	{
		$callable = [GLM::$ffi, 'glmc_mat3_'.$method];
		
		$res = $callable(...$args);
		
		//return $res;
		
		if ( ! GLM::ffi_returned_void( $res ) ) return $res;
		
		// Most time, the last parameter is a destination vector,
		// so we can return it instead of void.
		// The exceptions are :
		// - glmc_mat3_scale( m , s )
		// - glmc_mat3_swap_col(mat2 mat, int col1, int col2);
		// - glmc_mat3_swap_row(mat2 mat, int row1, int row2);
		// They will be overriden below. 
		
		return $args[ count( $args ) -1 ]; 
	}
	
	public static function new( $A = null )
	{
		$M = GLM::$ffi->new("mat3");
		
		if ( ! empty( $A ) )
		{
			$M[0][0] = $A[0][0] ?? $A[0] ?? 0.0 ;
			$M[0][1] = $A[0][1] ?? $A[1] ?? 0.0 ;
			$M[0][2] = $A[0][2] ?? $A[2] ?? 0.0 ;
			
			$M[1][0] = $A[1][0] ?? $A[3] ?? 0.0 ;
			$M[1][1] = $A[1][1] ?? $A[4] ?? 0.0 ;
			$M[1][2] = $A[1][2] ?? $A[5] ?? 0.0 ;
			
			$M[2][0] = $A[2][0] ?? $A[6] ?? 0.0 ;
			$M[2][1] = $A[2][1] ?? $A[7] ?? 0.0 ;
			$M[2][2] = $A[2][2] ?? $A[8] ?? 0.0 ;
		}
		
		return $M;
	}
	
	public static function clone( $A )
	{
		$D = GLM::$ffi->new("mat3");
		GLM::$ffi->glmc_mat3_copy( $A , $D );
		return $D;
	}
	
	public static function scale( $M , float $s )
	{
		GLM::$ffi->glmc_mat3_scale( $M , $s );
		
		return $M;
	}
	
	public static function swap_col( $M , int $col1, int $col2 )
	{
		GLM::$ffi->glmc_mat3_swap_col( $M , $col1 , $col2 );
		
		return $M;
	}
	
	public static function swap_row( $M, int $row1 , int $row2 )
	{
		GLM::$ffi->glmc_mat3_swap_row( $M , $row1 , $row2 );
		
		return $M;
	}
}


class Mat4
{
	public static function __callStatic( $method , $args )
	{
		$callable = [GLM::$ffi, 'glmc_mat4_'.$method];
		
		$res = $callable(...$args);
		
		//return $res;
		
		if ( ! GLM::ffi_returned_void( $res ) ) return $res;
		
		// Most time, the last parameter is a destination vector,
		// so we can return it instead of void.
		// The exceptions are :
		// - glmc_mat4_scale( m , s )
		// - glmc_mat4_scale_p( m , s )
		// - glmc_mat4_swap_col(mat2 mat, int col1, int col2);
		// - glmc_mat4_swap_row(mat2 mat, int row1, int row2);
		// They will be overriden below. 
		
		return $args[ count( $args ) -1 ]; 
	}
	
	public static function new( $A = null )
	{
		$M = GLM::$ffi->new("mat4");
		
		if ( ! empty( $A ) )
		{
			$M[0][0] = $A[0][0] ?? $A[0] ?? 0.0 ;
			$M[0][1] = $A[0][1] ?? $A[1] ?? 0.0 ;
			$M[0][2] = $A[0][2] ?? $A[2] ?? 0.0 ;
			$M[0][3] = $A[0][3] ?? $A[3] ?? 0.0 ;
			
			$M[1][0] = $A[1][0] ?? $A[4] ?? 0.0 ;
			$M[1][1] = $A[1][1] ?? $A[5] ?? 0.0 ;
			$M[1][2] = $A[1][2] ?? $A[6] ?? 0.0 ;
			$M[1][3] = $A[1][3] ?? $A[7] ?? 0.0 ;
			
			$M[2][0] = $A[2][0] ?? $A[8] ?? 0.0 ;
			$M[2][1] = $A[2][1] ?? $A[9] ?? 0.0 ;
			$M[2][2] = $A[2][2] ?? $A[10] ?? 0.0 ;
			$M[2][3] = $A[2][3] ?? $A[11] ?? 0.0 ;
			
			$M[3][0] = $A[3][0] ?? $A[12] ?? 0.0 ;
			$M[3][1] = $A[3][1] ?? $A[13] ?? 0.0 ;
			$M[3][2] = $A[3][2] ?? $A[14] ?? 0.0 ;
			$M[3][3] = $A[3][3] ?? $A[15] ?? 0.0 ;
		}
		
		return $M;
	}
	
	public static function clone( $A )
	{
		$D = GLM::$ffi->new("mat4");
		GLM::$ffi->glmc_mat3_copy( $A , $D );
		return $D;
	}
	
	public static function scale( $M , float $s )
	{
		GLM::$ffi->glmc_mat4_scale( $M , $s );
		
		return $M;
	}
	
	public static function scale_p( $M , float $s )
	{
		GLM::$ffi->glmc_mat4_scale_p( $M , $s );
		
		return $M;
	}
	
	public static function swap_col( $M , int $col1, int $col2 )
	{
		GLM::$ffi->glmc_mat4_swap_col( $M , $col1 , $col2 );
		
		return $M;
	}
	
	public static function swap_row( $M, int $row1 , int $row2 )
	{
		GLM::$ffi->glmc_mat4_swap_row( $M , $row1 , $row2 );
		
		return $M;
	}

}


class Aabb
{
	public static function __callStatic( $method , $args )
	{
		$callable = [GLM::$ffi, 'glmc_aabb_'.$method];
		
		$res = $callable(...$args);
		
		//return $res;
		
		if ( ! GLM::ffi_returned_void( $res ) ) return $res;
		
		// Most time, the last parameter is a destination vector,
		// so we can return it instead of void.
		// The exceptions are : TODO
		// ... 
		// They will be overriden below. 
		
		return $args[ count( $args ) -1 ]; 
	}
	
	public static function new( $A = null )
	{
		$B = GLM::$ffi->new("Aabb");
		
		if ( is_array( $A ) ) // [ [ Vmin ] , [ Vmax ] ]
		{
			GLM::$ffi->glmc_vec3_copy( $B->min , GLM::Vec3( $A[0] ) );
			GLM::$ffi->glmc_vec3_copy( $B->max , GLM::Vec3( $A[1] ) );
		}
		
		return $B;
	}
	
	public static function clone( $A )
	{
		$D = GLM::$ffi->new("Aabb");
		GLM::$ffi->glmc_vec3_copy( $A->min , $D->min );
		GLM::$ffi->glmc_vec3_copy( $A->max , $D->max );
		return $D;
	}
	
	public static function transform( $A , $M , $D=null )
	{
		$A = GLM::Aabb( $A );
		$D = GLM::Aabb( $D );
		
		GLM::$ffi->glmc_aabb_transform( $A->v , $M , $D->v );
		
		return $D;
	}
	
	public static function merge( $A , $B , $D=null )
	{
		$A = GLM::Aabb( $A );
		$B = GLM::Aabb( $B );
		$D = GLM::Aabb( $D );
		
		GLM::$ffi->glmc_aabb_merge( $A->v , $B->v , $D->v );
		
		return $D;
	}
	
	public static function crop( $A , $B , $D=null )
	{
		$A = GLM::Aabb( $A );
		$B = GLM::Aabb( $B );
		$D = GLM::Aabb( $D );
		
		GLM::$ffi->glmc_aabb_crop( $A->v , $B->v , $D->v );
		
		return $D;
	}
	
	public static function crop_until( $A , $B , $C , $D=null )
	{
		$A = GLM::Aabb( $A );
		$B = GLM::Aabb( $B );
		$C = GLM::Aabb( $C );
		$D = GLM::Aabb( $D );
		
		GLM::$ffi->glmc_aabb_crop( $A->v , $B->v , $C->v , $D->v );
		
		return $D;
	}
	
	public static function frustum( $A , $Planes=null )
	{
		$A = GLM::Aabb( $A );
		$Planes = GLM::FrustumPlanes( $Planes );
		
		return GLM::$ffi->glmc_aabb_frustum( $A->v , $Planes->v );
	}
	
	public static function invalidate( $A = null )
	{
		$A = GLM::Aabb( $A );
		
		GLM::$ffi->glmc_aabb_invalidate( $A->v );
		
		return $A;
	}
	
	public static function isvalid( $A )
	{
		$A = GLM::Aabb( $A );
		
		return GLM::$ffi->glmc_aabb_isvalid( $A->v );
	}
	
	public static function size( $A )
	{
		$A = GLM::Aabb( $A );
		
		return GLM::$ffi->glmc_aabb_size( $A->v );
	}
	
	public static function radius( $A )
	{
		$A = GLM::Aabb( $A );
		
		return GLM::$ffi->glmc_aabb_radius( $A->v );
	}
	
	public static function center( $A , $D=null )
	{
		$A = GLM::Aabb( $A );
		$D = GLM::Vec3( $D );
		
		GLM::$ffi->glmc_aabb_center( $A->v , $D );
		
		return $D;
	}
	
	public static function aabb( $A , $B )
	{
		$A = GLM::Aabb( $A );
		$B = GLM::Aabb( $B );
		
		return GLM::$ffi->glmc_aabb_aabb( $A->v , $B->v );
	}
	
	public static function point( $A , $P )
	{
		$A = GLM::Aabb( $A );
		$P = GLM::Vec3( $P );
		
		return GLM::$ffi->glmc_aabb_point( $A->v , $P );
	}
	
	public static function contains( $A , $B )
	{
		$A = GLM::Aabb( $A );
		$B = GLM::Aabb( $B );
		
		return GLM::$ffi->glmc_aabb_contains( $A->v , $B->v );
	}
	
	public static function sphere( $A , $S , $rad=null )
	{
		$A = GLM::Aabb( $A );
		$S = GLM::Vec4( $S , $rad ); 
		
		// Note : 
		// - if count( $S ) == 4, then, $rad will be ignored.
		// - if count( $S ) < 4 and $rad is null, Vec4() will set it to default 1.0
		
		return GLM::$ffi->glmc_aabb_sphere( $A->v , $S );
	}
}




class Quat
{
	public static function __callStatic( $method , $args )
	{
		$callable = [GLM::$ffi, 'glmc_quat_'.$method];
		
		$res = $callable(...$args);
		
		//return $res;
		
		if ( ! GLM::ffi_returned_void( $res ) ) return $res;
		
		// Most time, the last parameter is a destination vector,
		// so we can return it instead of void.
		// The exceptions are : 
		// - glmc_quat_init(versor q, float x, float y, float z, float w);
		// - glmc_quat(versor q, float angle, float x, float y, float z);
		// - glmc_quatv(versor q, float angle, vec3 axis);
		// - glmc_quat_normalize(versor q);
		// - glmc_quat_rotate_at(mat4 model, versor q, vec3 pivot);
		// - glmc_quat_rotate_atm(mat4 m, versor q, vec3 pivot);
		// They will be overriden below. 
		
		return $args[ count( $args ) -1 ]; 
	}
	
	public static function new( $A = null )
	{
		$V = GLM::$ffi->new("versor"); //!\ 'versor' is the name used by cglm for quaternion
		
		if ( ! empty( $A ) )
		{
			$V[0] = $A[0] ?? 0.0 ;
			$V[1] = $A[1] ?? 0.0 ;
			$V[2] = $A[2] ?? 0.0 ;
			$V[3] = $A[3] ?? 0.0 ; // ZERO
		}
		
		return $V;
	}
	
	public static function clone( $A )
	{
		$D = GLM::$ffi->new("versor"); //!\ 'versor' is the name used by cglm for quaternion
		GLM::$ffi->glmc_quat_copy( $A , $D );
		return $D;
	}
	
	public static function init( $Q , $V , float $angle = null )
	{
		if ( $angle === null )
		{  
			// glmc_quat_init(versor q, float x, float y, float z, float w);
			GLM::$ffi->glmc_quat_init( $Q , $V[0] , $V[1] , $V[2] , $V[3] );
		}
		else
		{
			// glmc_quat(versor q, float angle, float x, float y, float z);
			// glmc_quatv(versor q, float angle, vec3 axis);
			$V = GLM::Vec3( $V );
			GLM::$ffi->glmc_quatv( $Q , $angle , $V );
		}
		
		return $Q;
	}
	
	
	public static function normalize( $Q )
	{
		$Q = GLM::Quat( $Q );
		GLM::$ffi->glmc_quat_normalize( $Q );
		return $Q;
	}
	
	public static function rotate_at( $M , $Q , $V_pivot )
	{
		$Q       = GLM::Quat( $Q       );
		$V_pivot = GLM::Vec3( $V_pivot );
		
		GLM::$ffi->glmc_quat_rotate_at( $M , $Q , $V_pivot );
		
		return $M;
	}
	
	public static function rotate_at_m( $M , $Q , $V_pivot )
	{
		$Q       = GLM::Quat( $Q       );
		$V_pivot = GLM::Vec3( $V_pivot );
		
		GLM::$ffi->glmc_quat_rotate_atm( $M , $Q , $V_pivot );
		
		return $M;
	}
}


