#define FFI_LIB "./lib/libcglm.so"


// types.h ------------------------------------------------------------

typedef float                   vec2[2];
typedef float                   vec3[3];
typedef int                    ivec3[3];
typedef float /*CGLM_ALIGN_IF(16)*/ vec4[4];
typedef vec4  /*CGLM_ALIGN_IF(16)*/ versor;     /* |x, y, z, w| -> w is the last */
typedef vec3                    mat3[3];
typedef vec2                    mat2[2];
typedef vec4  /*CGLM_ALIGN_MAT*/ mat4[4];



// custom types helpers :

typedef union
{
	vec3 v[2];
	struct { vec3 min, max ; }
}
Aabb;

typedef union 
{ 
	vec4 v[6]; 
	struct { vec4 left, right, bottom, top, near, far; }
}
FrustumPlanes;

typedef union 
{ 
	vec4 v[8];
	struct { vec4 LBN,LTN,RTN,RBN, LBF,LTF,RTF,RBF; }
} 
FrustumCorners;

typedef union 
{ 
	vec4 v[4];
	struct { vec4 LB,LT,RT,RB; }
} 
PlaneCorners;


// vec2.h --------------------------------------------------------------------------------------------------------------

void
glmc_vec2(float * /*__restrict*/ v, vec2 dest);

void
glmc_vec2_copy(vec2 a, vec2 dest);

void
glmc_vec2_zero(vec2 v);

void
glmc_vec2_one(vec2 v);

float
glmc_vec2_dot(vec2 a, vec2 b);

float
glmc_vec2_cross(vec2 a, vec2 b);

float
glmc_vec2_norm2(vec2 v);

float
glmc_vec2_norm(vec2 v);

void
glmc_vec2_add(vec2 a, vec2 b, vec2 dest);

void
glmc_vec2_adds(vec2 v, float s, vec2 dest);

void
glmc_vec2_sub(vec2 a, vec2 b, vec2 dest);

void
glmc_vec2_subs(vec2 v, float s, vec2 dest);

void
glmc_vec2_mul(vec2 a, vec2 b, vec2 dest);

void
glmc_vec2_scale(vec2 v, float s, vec2 dest);

void
glmc_vec2_scale_as(vec2 v, float s, vec2 dest);

void
glmc_vec2_div(vec2 a, vec2 b, vec2 dest);

void
glmc_vec2_divs(vec2 v, float s, vec2 dest);

void
glmc_vec2_addadd(vec2 a, vec2 b, vec2 dest);

void
glmc_vec2_subadd(vec2 a, vec2 b, vec2 dest);

void
glmc_vec2_muladd(vec2 a, vec2 b, vec2 dest);

void
glmc_vec2_muladds(vec2 a, float s, vec2 dest);

void
glmc_vec2_maxadd(vec2 a, vec2 b, vec2 dest);

void
glmc_vec2_minadd(vec2 a, vec2 b, vec2 dest);

void
glmc_vec2_negate_to(vec2 v, vec2 dest);

void
glmc_vec2_negate(vec2 v);

void
glmc_vec2_normalize(vec2 v);

void
glmc_vec2_normalize_to(vec2 v, vec2 dest);

void
glmc_vec2_rotate(vec2 v, float angle, vec2 dest);

float
glmc_vec2_distance2(vec2 a, vec2 b);

float
glmc_vec2_distance(vec2 a, vec2 b);

void
glmc_vec2_maxv(vec2 a, vec2 b, vec2 dest);

void
glmc_vec2_minv(vec2 a, vec2 b, vec2 dest);

void
glmc_vec2_clamp(vec2 v, float minval, float maxval);

void
glmc_vec2_lerp(vec2 from, vec2 to, float t, vec2 dest);


// vec3.h --------------------------------------------------------------------------------------------------------------

void
glmc_vec3(vec4 v4, vec3 dest);

void
glmc_vec3_copy(vec3 a, vec3 dest);

void
glmc_vec3_zero(vec3 v);

void
glmc_vec3_one(vec3 v);

float
glmc_vec3_dot(vec3 a, vec3 b);

void
glmc_vec3_cross(vec3 a, vec3 b, vec3 dest);

void
glmc_vec3_crossn(vec3 a, vec3 b, vec3 dest);

float
glmc_vec3_norm(vec3 v);

float
glmc_vec3_norm2(vec3 v);

float
glmc_vec3_norm_one(vec3 v);

float
glmc_vec3_norm_inf(vec3 v);

void
glmc_vec3_normalize_to(vec3 v, vec3 dest);

void
glmc_vec3_normalize(vec3 v);

void
glmc_vec3_add(vec3 a, vec3 b, vec3 dest);

void
glmc_vec3_adds(vec3 v, float s, vec3 dest);

void
glmc_vec3_sub(vec3 a, vec3 b, vec3 dest);

void
glmc_vec3_subs(vec3 v, float s, vec3 dest);

void
glmc_vec3_mul(vec3 a, vec3 b, vec3 d);

void
glmc_vec3_scale(vec3 v, float s, vec3 dest);

void
glmc_vec3_scale_as(vec3 v, float s, vec3 dest);

void
glmc_vec3_div(vec3 a, vec3 b, vec3 dest);

void
glmc_vec3_divs(vec3 a, float s, vec3 dest);

void
glmc_vec3_addadd(vec3 a, vec3 b, vec3 dest);

void
glmc_vec3_subadd(vec3 a, vec3 b, vec3 dest);

void
glmc_vec3_muladd(vec3 a, vec3 b, vec3 dest);

void
glmc_vec3_muladds(vec3 a, float s, vec3 dest);

void
glmc_vec3_maxadd(vec3 a, vec3 b, vec3 dest);

void
glmc_vec3_minadd(vec3 a, vec3 b, vec3 dest);

void
glmc_vec3_negate(vec3 v);

void
glmc_vec3_negate_to(vec3 v, vec3 dest);

float
glmc_vec3_angle(vec3 a, vec3 b);

void
glmc_vec3_rotate(vec3 v, float angle, vec3 axis);

void
glmc_vec3_rotate_m4(mat4 m, vec3 v, vec3 dest);

void
glmc_vec3_rotate_m3(mat3 m, vec3 v, vec3 dest);

void
glmc_vec3_proj(vec3 a, vec3 b, vec3 dest);

void
glmc_vec3_center(vec3 a, vec3 b, vec3 dest);

float
glmc_vec3_distance2(vec3 a, vec3 b);

float
glmc_vec3_distance(vec3 a, vec3 b);

void
glmc_vec3_maxv(vec3 a, vec3 b, vec3 dest);

void
glmc_vec3_minv(vec3 a, vec3 b, vec3 dest);

void
glmc_vec3_clamp(vec3 v, float minVal, float maxVal);

void
glmc_vec3_ortho(vec3 v, vec3 dest);

void
glmc_vec3_lerp(vec3 from, vec3 to, float t, vec3 dest);

void
glmc_vec3_lerpc(vec3 from, vec3 to, float t, vec3 dest);

//CGLM_INLINE
//void
//glmc_vec3_mix(vec3 from, vec3 to, float t, vec3 dest) {
//  glmc_vec3_lerp(from, to, t, dest);
//}
//
//CGLM_INLINE
//void
//glmc_vec3_mixc(vec3 from, vec3 to, float t, vec3 dest) {
//  glmc_vec3_lerpc(from, to, t, dest);
//}

void
glmc_vec3_step_uni(float edge, vec3 x, vec3 dest);

void
glmc_vec3_step(vec3 edge, vec3 x, vec3 dest);

void
glmc_vec3_smoothstep_uni(float edge0, float edge1, vec3 x, vec3 dest);

void
glmc_vec3_smoothstep(vec3 edge0, vec3 edge1, vec3 x, vec3 dest);

void
glmc_vec3_smoothinterp(vec3 from, vec3 to, float t, vec3 dest);

void
glmc_vec3_smoothinterpc(vec3 from, vec3 to, float t, vec3 dest);

/* ext */

void
glmc_vec3_mulv(vec3 a, vec3 b, vec3 d);

void
glmc_vec3_broadcast(float val, vec3 d);

void
glmc_vec3_fill(vec3 v, float val);

bool
glmc_vec3_eq(vec3 v, float val);

bool
glmc_vec3_eq_eps(vec3 v, float val);

bool
glmc_vec3_eq_all(vec3 v);

bool
glmc_vec3_eqv(vec3 a, vec3 b);

bool
glmc_vec3_eqv_eps(vec3 a, vec3 b);

float
glmc_vec3_max(vec3 v);

float
glmc_vec3_min(vec3 v);

bool
glmc_vec3_isnan(vec3 v);

bool
glmc_vec3_isinf(vec3 v);

bool
glmc_vec3_isvalid(vec3 v);

void
glmc_vec3_sign(vec3 v, vec3 dest);

void
glmc_vec3_abs(vec3 v, vec3 dest);

void
glmc_vec3_fract(vec3 v, vec3 dest);

float
glmc_vec3_hadd(vec3 v);

void
glmc_vec3_sqrt(vec3 v, vec3 dest);


// vec4.h --------------------------------------------------------------------------------------------------------------

void
glmc_vec4(vec3 v3, float last, vec4 dest);

void
glmc_vec4_zero(vec4 v);

void
glmc_vec4_one(vec4 v);

void
glmc_vec4_copy3(vec4 v, vec3 dest);

void
glmc_vec4_copy(vec4 v, vec4 dest);

void
glmc_vec4_ucopy(vec4 v, vec4 dest);

float
glmc_vec4_dot(vec4 a, vec4 b);

float
glmc_vec4_norm(vec4 v);

float
glmc_vec4_norm2(vec4 v);

float
glmc_vec4_norm_one(vec4 v);

float
glmc_vec4_norm_inf(vec4 v);

void
glmc_vec4_normalize_to(vec4 v, vec4 dest);

void
glmc_vec4_normalize(vec4 v);

void
glmc_vec4_add(vec4 a, vec4 b, vec4 dest);

void
glmc_vec4_adds(vec4 v, float s, vec4 dest);

void
glmc_vec4_sub(vec4 a, vec4 b, vec4 dest);

void
glmc_vec4_subs(vec4 v, float s, vec4 dest);

void
glmc_vec4_mul(vec4 a, vec4 b, vec4 d);

void
glmc_vec4_scale(vec4 v, float s, vec4 dest);

void
glmc_vec4_scale_as(vec4 v, float s, vec4 dest);

void
glmc_vec4_div(vec4 a, vec4 b, vec4 dest);

void
glmc_vec4_divs(vec4 v, float s, vec4 dest);

void
glmc_vec4_addadd(vec4 a, vec4 b, vec4 dest);

void
glmc_vec4_subadd(vec4 a, vec4 b, vec4 dest);

void
glmc_vec4_muladd(vec4 a, vec4 b, vec4 dest);

void
glmc_vec4_muladds(vec4 a, float s, vec4 dest);

void
glmc_vec4_maxadd(vec4 a, vec4 b, vec4 dest);

void
glmc_vec4_minadd(vec4 a, vec4 b, vec4 dest);

void
glmc_vec4_negate(vec4 v);

void
glmc_vec4_negate_to(vec4 v, vec4 dest);

float
glmc_vec4_distance(vec4 a, vec4 b);

float
glmc_vec4_distance2(vec4 a, vec4 b);

void
glmc_vec4_maxv(vec4 a, vec4 b, vec4 dest);

void
glmc_vec4_minv(vec4 a, vec4 b, vec4 dest);

void
glmc_vec4_clamp(vec4 v, float minVal, float maxVal);

void
glmc_vec4_lerp(vec4 from, vec4 to, float t, vec4 dest);

void
glmc_vec4_lerpc(vec4 from, vec4 to, float t, vec4 dest);

//CGLM_INLINE
//void
//glmc_vec4_mix(vec4 from, vec4 to, float t, vec4 dest) {
//  glmc_vec4_lerp(from, to, t, dest);
//}
//
//CGLM_INLINE
//void
//glmc_vec4_mixc(vec4 from, vec4 to, float t, vec4 dest) {
//  glmc_vec4_lerpc(from, to, t, dest);
//}

void
glmc_vec4_step_uni(float edge, vec4 x, vec4 dest);

void
glmc_vec4_step(vec4 edge, vec4 x, vec4 dest);

void
glmc_vec4_smoothstep_uni(float edge0, float edge1, vec4 x, vec4 dest);

void
glmc_vec4_smoothstep(vec4 edge0, vec4 edge1, vec4 x, vec4 dest);

void
glmc_vec4_smoothinterp(vec4 from, vec4 to, float t, vec4 dest);

void
glmc_vec4_smoothinterpc(vec4 from, vec4 to, float t, vec4 dest);

void
glmc_vec4_cubic(float s, vec4 dest);

/* ext */

void
glmc_vec4_mulv(vec4 a, vec4 b, vec4 d);

void
glmc_vec4_broadcast(float val, vec4 d);

void
glmc_vec4_fill(vec4 v, float val);

bool
glmc_vec4_eq(vec4 v, float val);

bool
glmc_vec4_eq_eps(vec4 v, float val);

bool
glmc_vec4_eq_all(vec4 v);

bool
glmc_vec4_eqv(vec4 a, vec4 b);

bool
glmc_vec4_eqv_eps(vec4 a, vec4 b);

float
glmc_vec4_max(vec4 v);

float
glmc_vec4_min(vec4 v);

bool
glmc_vec4_isnan(vec4 v);

bool
glmc_vec4_isinf(vec4 v);

bool
glmc_vec4_isvalid(vec4 v);

void
glmc_vec4_sign(vec4 v, vec4 dest);

void
glmc_vec4_abs(vec4 v, vec4 dest);

void
glmc_vec4_fract(vec4 v, vec4 dest);

float
glmc_vec4_hadd(vec4 v);

void
glmc_vec4_sqrt(vec4 v, vec4 dest);


// mat2.h --------------------------------------------------------------------------------------------------------------

void
glmc_mat2_copy(mat2 mat, mat2 dest);

void
glmc_mat2_identity(mat2 mat);

// TODO ?
//void
//glmc_mat2_identity_array(mat2 * /*__restrict*/ mat, size_t count);

void
glmc_mat2_zero(mat2 mat);

void
glmc_mat2_mul(mat2 m1, mat2 m2, mat2 dest);

void
glmc_mat2_transpose_to(mat2 m, mat2 dest);

void
glmc_mat2_transpose(mat2 m);

void
glmc_mat2_mulv(mat2 m, vec2 v, vec2 dest);

float
glmc_mat2_trace(mat2 m);

void
glmc_mat2_scale(mat2 m, float s);

float
glmc_mat2_det(mat2 mat);

void
glmc_mat2_inv(mat2 mat, mat2 dest);

void
glmc_mat2_swap_col(mat2 mat, int col1, int col2);

void
glmc_mat2_swap_row(mat2 mat, int row1, int row2);

float
glmc_mat2_rmc(vec2 r, mat2 m, vec2 c);


// mat3.h --------------------------------------------------------------------------------------------------------------

void
glmc_mat3_copy(mat3 mat, mat3 dest);

void
glmc_mat3_identity(mat3 mat);

void
glmc_mat3_zero(mat3 mat);

// TODO ?
//void
//glmc_mat3_identity_array(mat3 * /*__restrict*/ mat, size_t count);

void
glmc_mat3_mul(mat3 m1, mat3 m2, mat3 dest);

void
glmc_mat3_transpose_to(mat3 m, mat3 dest);

void
glmc_mat3_transpose(mat3 m);

void
glmc_mat3_mulv(mat3 m, vec3 v, vec3 dest);

float
glmc_mat3_trace(mat3 m);

void
glmc_mat3_quat(mat3 m, versor dest);

void
glmc_mat3_scale(mat3 m, float s);

float
glmc_mat3_det(mat3 mat);

void
glmc_mat3_inv(mat3 mat, mat3 dest);

void
glmc_mat3_swap_col(mat3 mat, int col1, int col2);

void
glmc_mat3_swap_row(mat3 mat, int row1, int row2);

float
glmc_mat3_rmc(vec3 r, mat3 m, vec3 c);


// mat4.h --------------------------------------------------------------------------------------------------------------


void
glmc_mat4_ucopy(mat4 mat, mat4 dest);

void
glmc_mat4_copy(mat4 mat, mat4 dest);

void
glmc_mat4_identity(mat4 mat);

// TODO ?
//void
//glmc_mat4_identity_array(mat4 * /*__restrict*/ mat, size_t count);

void
glmc_mat4_zero(mat4 mat);

void
glmc_mat4_pick3(mat4 mat, mat3 dest);

void
glmc_mat4_pick3t(mat4 mat, mat3 dest);

void
glmc_mat4_ins3(mat3 mat, mat4 dest);

void
glmc_mat4_mul(mat4 m1, mat4 m2, mat4 dest);

void
glmc_mat4_mulN(mat4 * /*__restrict*/ matrices[], uint32_t len, mat4 dest);

void
glmc_mat4_mulv(mat4 m, vec4 v, vec4 dest);

void
glmc_mat4_mulv3(mat4 m, vec3 v, float last, vec3 dest);

float
glmc_mat4_trace(mat4 m);

float
glmc_mat4_trace3(mat4 m);

void
glmc_mat4_quat(mat4 m, versor dest);

void
glmc_mat4_transpose_to(mat4 m, mat4 dest);

void
glmc_mat4_transpose(mat4 m);

void
glmc_mat4_scale_p(mat4 m, float s);

void
glmc_mat4_scale(mat4 m, float s);

float
glmc_mat4_det(mat4 mat);

void
glmc_mat4_inv(mat4 mat, mat4 dest);

void
glmc_mat4_inv_precise(mat4 mat, mat4 dest);

void
glmc_mat4_inv_fast(mat4 mat, mat4 dest);

void
glmc_mat4_swap_col(mat4 mat, int col1, int col2);

void
glmc_mat4_swap_row(mat4 mat, int row1, int row2);

float
glmc_mat4_rmc(vec4 r, mat4 m, vec4 c);


// affine.h --------------------------------------------------------------------------------------------------------------


void
glmc_translate_make(mat4 m, vec3 v);

void
glmc_translate_to(mat4 m, vec3 v, mat4 dest);

void
glmc_translate(mat4 m, vec3 v);

void
glmc_translate_x(mat4 m, float to);

void
glmc_translate_y(mat4 m, float to);

void
glmc_translate_z(mat4 m, float to);

void
glmc_scale_make(mat4 m, vec3 v);

void
glmc_scale_to(mat4 m, vec3 v, mat4 dest);

void
glmc_scale(mat4 m, vec3 v);

void
glmc_scale_uni(mat4 m, float s);

void
glmc_rotate_x(mat4 m, float rad, mat4 dest);

void
glmc_rotate_y(mat4 m, float rad, mat4 dest);

void
glmc_rotate_z(mat4 m, float rad, mat4 dest);

void
glmc_rotate_make(mat4 m, float angle, vec3 axis);

void
glmc_rotate(mat4 m, float angle, vec3 axis);

void
glmc_rotate_at(mat4 m, vec3 pivot, float angle, vec3 axis);

// TODO ?
//void
//glmc_rotate_atm(mat4 m, vec3 pivot, float angle, vec3 axis);

void
glmc_decompose_scalev(mat4 m, vec3 s);

bool
glmc_uniscaled(mat4 m);

void
glmc_decompose_rs(mat4 m, mat4 r, vec3 s);

void
glmc_decompose(mat4 m, vec4 t, mat4 r, vec3 s);

/* affine-mat */

void
glmc_mul(mat4 m1, mat4 m2, mat4 dest);

void
glmc_mul_rot(mat4 m1, mat4 m2, mat4 dest);

void
glmc_inv_tr(mat4 mat);


// cam.h --------------------------------------------------------------------------------------------------------------


void
glmc_frustum(float left,   float right,
             float bottom, float top,
             float nearZ,  float farZ,
             mat4  dest);

void
glmc_ortho(float left,   float right,
           float bottom, float top,
           float nearZ,  float farZ,
           mat4  dest);

void
glmc_ortho_aabb(vec3 box[2], mat4 dest);

void
glmc_ortho_aabb_p(vec3 box[2], float padding, mat4 dest);

void
glmc_ortho_aabb_pz(vec3 box[2], float padding, mat4 dest);

void
glmc_ortho_default(float aspect, mat4 dest);

void
glmc_ortho_default_s(float aspect, float size, mat4 dest);

void
glmc_perspective(float fovy, float aspect, float nearZ, float farZ, mat4 dest);

void
glmc_persp_move_far(mat4 proj, float deltaFar);

void
glmc_perspective_default(float aspect, mat4 dest);

void
glmc_perspective_resize(float aspect, mat4 proj);

void
glmc_lookat(vec3 eye, vec3 center, vec3 up, mat4 dest);

void
glmc_look(vec3 eye, vec3 dir, vec3 up, mat4 dest);

void
glmc_look_anyup(vec3 eye, vec3 dir, mat4 dest);

void
glmc_persp_decomp(mat4 proj,
                  float * /*__restrict*/ nearZ,
                  float * /*__restrict*/ farZ,
                  float * /*__restrict*/ top,
                  float * /*__restrict*/ bottom,
                  float * /*__restrict*/ left,
                  float * /*__restrict*/ right);

void
glmc_persp_decompv(mat4 proj, float dest[6]);

void
glmc_persp_decomp_x(mat4 proj,
                    float * /*__restrict*/ left,
                    float * /*__restrict*/ right);

void
glmc_persp_decomp_y(mat4 proj,
                    float * /*__restrict*/ top,
                    float * /*__restrict*/ bottom);

void
glmc_persp_decomp_z(mat4 proj,
                    float * /*__restrict*/ nearZ,
                    float * /*__restrict*/ farZ);

void
glmc_persp_decomp_far(mat4 proj, float * /*__restrict*/ farZ);

void
glmc_persp_decomp_near(mat4 proj, float * /*__restrict*/ nearZ);

float
glmc_persp_fovy(mat4 proj);

float
glmc_persp_aspect(mat4 proj);

void
glmc_persp_sizes(mat4 proj, float fovy, vec4 dest);



// quat.h --------------------------------------------------------------------------------------------------------------


void
glmc_quat_identity(versor q);

// TODO ?
//void
//glmc_quat_identity_array(versor * /*__restrict*/ q, size_t count);

void
glmc_quat_init(versor q, float x, float y, float z, float w);

void
glmc_quat(versor q, float angle, float x, float y, float z);

void
glmc_quatv(versor q, float angle, vec3 axis);

void
glmc_quat_copy(versor q, versor dest);

void
glmc_quat_from_vecs(vec3 a, vec3 b, versor dest);

float
glmc_quat_norm(versor q);

void
glmc_quat_normalize_to(versor q, versor dest);

void
glmc_quat_normalize(versor q);

float
glmc_quat_dot(versor p, versor q);

void
glmc_quat_conjugate(versor q, versor dest);

void
glmc_quat_inv(versor q, versor dest);

void
glmc_quat_add(versor p, versor q, versor dest);

void
glmc_quat_sub(versor p, versor q, versor dest);

float
glmc_quat_real(versor q);

void
glmc_quat_imag(versor q, vec3 dest);

void
glmc_quat_imagn(versor q, vec3 dest);

float
glmc_quat_imaglen(versor q);

float
glmc_quat_angle(versor q);

void
glmc_quat_axis(versor q, vec3 dest);

void
glmc_quat_mul(versor p, versor q, versor dest);

void
glmc_quat_mat4(versor q, mat4 dest);

void
glmc_quat_mat4t(versor q, mat4 dest);

void
glmc_quat_mat3(versor q, mat3 dest);

void
glmc_quat_mat3t(versor q, mat3 dest);

void
glmc_quat_lerp(versor from, versor to, float t, versor dest);

void
glmc_quat_lerpc(versor from, versor to, float t, versor dest);

void
glmc_quat_nlerp(versor q, versor r, float t, versor dest);

void
glmc_quat_slerp(versor q, versor r, float t, versor dest);

void
glmc_quat_look(vec3 eye, versor ori, mat4 dest);

void
glmc_quat_for(vec3 dir, vec3 up, versor dest);

void
glmc_quat_forp(vec3 from, vec3 to, vec3 up, versor dest);

void
glmc_quat_rotatev(versor from, vec3 to, vec3 dest);

void
glmc_quat_rotate(mat4 m, versor q, mat4 dest);

void
glmc_quat_rotate_at(mat4 model, versor q, vec3 pivot);

void
glmc_quat_rotate_atm(mat4 m, versor q, vec3 pivot);


// euler.h --------------------------------------------------------------------------------------------------------------

typedef enum glm_euler_seq {
  GLM_EULER_XYZ = 0 << 0 | 1 << 2 | 2 << 4,
  GLM_EULER_XZY = 0 << 0 | 2 << 2 | 1 << 4,
  GLM_EULER_YZX = 1 << 0 | 2 << 2 | 0 << 4,
  GLM_EULER_YXZ = 1 << 0 | 0 << 2 | 2 << 4,
  GLM_EULER_ZXY = 2 << 0 | 0 << 2 | 1 << 4,
  GLM_EULER_ZYX = 2 << 0 | 1 << 2 | 0 << 4
} glm_euler_seq;

void
glmc_euler_angles(mat4 m, vec3 dest);

void
glmc_euler(vec3 angles, mat4 dest);

void
glmc_euler_xyz(vec3 angles,  mat4 dest);

void
glmc_euler_zyx(vec3 angles,  mat4 dest);

void
glmc_euler_zxy(vec3 angles, mat4 dest);

void
glmc_euler_xzy(vec3 angles, mat4 dest);

void
glmc_euler_yzx(vec3 angles, mat4 dest);

void
glmc_euler_yxz(vec3 angles, mat4 dest);

//TODO ??
//void
//glmc_euler_by_order(vec3 angles, glm_euler_seq axis, mat4 dest);


// plane.h --------------------------------------------------------------------------------------------------------------

void
glmc_plane_normalize(vec4 plane);


// frustum.h --------------------------------------------------------------------------------------------------------------


void
glmc_frustum_planes(mat4 m, vec4 dest[6]);

void
glmc_frustum_corners(mat4 invMat, vec4 dest[8]);

void
glmc_frustum_center(vec4 corners[8], vec4 dest);

void
glmc_frustum_box(vec4 corners[8], mat4 m, vec3 box[2]);

void
glmc_frustum_corners_at(vec4  corners[8],
                        float splitDist,
                        float farDist,
                        vec4  planeCorners[4]);


// box.h --------------------------------------------------------------------------------------------------------------


void
glmc_aabb_transform(vec3 box[2], mat4 m, vec3 dest[2]);

void
glmc_aabb_merge(vec3 box1[2], vec3 box2[2], vec3 dest[2]);

void
glmc_aabb_crop(vec3 box[2], vec3 cropBox[2], vec3 dest[2]);

void
glmc_aabb_crop_until(vec3 box[2],
                     vec3 cropBox[2],
                     vec3 clampBox[2],
                     vec3 dest[2]);

bool
glmc_aabb_frustum(vec3 box[2], vec4 planes[6]);

void
glmc_aabb_invalidate(vec3 box[2]);

bool
glmc_aabb_isvalid(vec3 box[2]);

float
glmc_aabb_size(vec3 box[2]);

float
glmc_aabb_radius(vec3 box[2]);

void
glmc_aabb_center(vec3 box[2], vec3 dest);

bool
glmc_aabb_aabb(vec3 box[2], vec3 other[2]);

bool
glmc_aabb_point(vec3 box[2], vec3 point);

bool
glmc_aabb_contains(vec3 box[2], vec3 other[2]);

bool
glmc_aabb_sphere(vec3 box[2], vec4 s);


// io.h --------------------------------------------------------------------------------------------------------------

// TODO
//void
//glmc_mat4_print(mat4   matrix,
//                FILE * /*__restrict*/ ostream);
//
//void
//glmc_mat3_print(mat3 matrix,
//                FILE * /*__restrict*/ ostream);
//
//void
//glmc_vec4_print(vec4 vec,
//                FILE * /*__restrict*/ ostream);
//
//void
//glmc_vec3_print(vec3 vec,
//                FILE * /*__restrict*/ ostream);
//
//void
//glmc_versor_print(versor vec,
//                  FILE * /*__restrict*/ ostream);


// project.h --------------------------------------------------------------------------------------------------------------


void
glmc_unprojecti(vec3 pos, mat4 invMat, vec4 vp, vec3 dest);

void
glmc_unproject(vec3 pos, mat4 m, vec4 vp, vec3 dest);

void
glmc_project(vec3 pos, mat4 m, vec4 vp, vec3 dest);


// sphere.h --------------------------------------------------------------------------------------------------------------


float
glmc_sphere_radii(vec4 s);

void
glmc_sphere_transform(vec4 s, mat4 m, vec4 dest);

void
glmc_sphere_merge(vec4 s1, vec4 s2, vec4 dest);

bool
glmc_sphere_sphere(vec4 s1, vec4 s2);

bool
glmc_sphere_point(vec4 s, vec3 point);


// ease.h --------------------------------------------------------------------------------------------------------------


float
glmc_ease_linear(float t);

float
glmc_ease_sine_in(float t);

float
glmc_ease_sine_out(float t);

float
glmc_ease_sine_inout(float t);

float
glmc_ease_quad_in(float t);

float
glmc_ease_quad_out(float t);

float
glmc_ease_quad_inout(float t);

float
glmc_ease_cubic_in(float t);

float
glmc_ease_cubic_out(float t);

float
glmc_ease_cubic_inout(float t);

float
glmc_ease_quart_in(float t);

float
glmc_ease_quart_out(float t);

float
glmc_ease_quart_inout(float t);

float
glmc_ease_quint_in(float t);

float
glmc_ease_quint_out(float t);

float
glmc_ease_quint_inout(float t);

float
glmc_ease_exp_in(float t);

float
glmc_ease_exp_out(float t);

float
glmc_ease_exp_inout(float t);

float
glmc_ease_circ_in(float t);

float
glmc_ease_circ_out(float t);

float
glmc_ease_circ_inout(float t);

float
glmc_ease_back_in(float t);

float
glmc_ease_back_out(float t);

float
glmc_ease_back_inout(float t);

float
glmc_ease_elast_in(float t);

float
glmc_ease_elast_out(float t);

float
glmc_ease_elast_inout(float t);

float
glmc_ease_bounce_out(float t);

float
glmc_ease_bounce_in(float t);

float
glmc_ease_bounce_inout(float t);


// curve.h --------------------------------------------------------------------------------------------------------------

float
glmc_smc(float s, mat4 m, vec4 c);


// bezier.h --------------------------------------------------------------------------------------------------------------

float
glmc_bezier(float s, float p0, float c0, float c1, float p1);

float
glmc_hermite(float s, float p0, float t0, float t1, float p1);

float
glmc_decasteljau(float prm, float p0, float c0, float c1, float p1);


// ray.h --------------------------------------------------------------------------------------------------------------

bool
glmc_ray_triangle(vec3   origin,
                  vec3   direction,
                  vec3   v0,
                  vec3   v1,
                  vec3   v2,
                  float *d);


// affine2d.h --------------------------------------------------------------------------------------------------------------


void
glmc_translate2d_make(mat3 m, vec2 v);

void
glmc_translate2d_to(mat3 m, vec2 v, mat3 dest);

void
glmc_translate2d(mat3 m, vec2 v);

void
glmc_translate2d_x(mat3 m, float to);

void
glmc_translate2d_y(mat3 m, float to);

void
glmc_scale2d_to(mat3 m, vec2 v, mat3 dest);

void
glmc_scale2d_make(mat3 m, vec2 v);

void
glmc_scale2d(mat3 m, vec2 v);

void
glmc_scale2d_uni(mat3 m, float s);

void
glmc_rotate2d_make(mat3 m, float angle);

void
glmc_rotate2d(mat3 m, float angle);

void
glmc_rotate2d_to(mat3 m, float angle, mat3 dest);



// clipspace/ortho_lh_no.h----------------------------------------------------------------------------------------------


void
glmc_ortho_lh_no(float left,    float right,
                 float bottom,  float top,
                 float nearZ,   float farZ,
                 mat4  dest);

void
glmc_ortho_aabb_lh_no(vec3 box[2], mat4 dest);

void
glmc_ortho_aabb_p_lh_no(vec3 box[2], float padding, mat4 dest);

void
glmc_ortho_aabb_pz_lh_no(vec3 box[2], float padding, mat4 dest);

void
glmc_ortho_default_lh_no(float aspect, mat4 dest);

void
glmc_ortho_default_s_lh_no(float aspect, float size, mat4 dest);



// clipspace/ortho_lh_zo.h----------------------------------------------------------------------------------------------


void
glmc_ortho_lh_zo(float left,    float right,
                 float bottom,  float top,
                 float nearZ,   float farZ,
                 mat4  dest);

void
glmc_ortho_aabb_lh_zo(vec3 box[2], mat4 dest);

void
glmc_ortho_aabb_p_lh_zo(vec3 box[2], float padding, mat4 dest);

void
glmc_ortho_aabb_pz_lh_zo(vec3 box[2], float padding, mat4 dest);

void
glmc_ortho_default_lh_zo(float aspect, mat4 dest);

void
glmc_ortho_default_s_lh_zo(float aspect, float size, mat4 dest);



// clipspace/ortho_rh_no.h----------------------------------------------------------------------------------------------


void
glmc_ortho_rh_no(float left,    float right,
                 float bottom,  float top,
                 float nearZ,   float farZ,
                 mat4  dest);

void
glmc_ortho_aabb_rh_no(vec3 box[2], mat4 dest);

void
glmc_ortho_aabb_p_rh_no(vec3 box[2], float padding, mat4 dest);

void
glmc_ortho_aabb_pz_rh_no(vec3 box[2], float padding, mat4 dest);

void
glmc_ortho_default_rh_no(float aspect, mat4 dest);

void
glmc_ortho_default_s_rh_no(float aspect, float size, mat4 dest);


// clipspace/ortho_rh_zo.h----------------------------------------------------------------------------------------------

void
glmc_ortho_rh_zo(float left,    float right,
                 float bottom,  float top,
                 float nearZ,   float farZ,
                 mat4  dest);

void
glmc_ortho_aabb_rh_zo(vec3 box[2], mat4 dest);

void
glmc_ortho_aabb_p_rh_zo(vec3 box[2], float padding, mat4 dest);

void
glmc_ortho_aabb_pz_rh_zo(vec3 box[2], float padding, mat4 dest);

void
glmc_ortho_default_rh_zo(float aspect, mat4 dest);

void
glmc_ortho_default_s_rh_zo(float aspect, float size, mat4 dest);



// clipspace/persp_lh_no.h----------------------------------------------------------------------------------------------

void
glmc_frustum_lh_no(float left,    float right,
                   float bottom,  float top,
                   float nearZ,   float farZ,
                   mat4  dest);

void
glmc_perspective_lh_no(float fovy,
                       float aspect,
                       float nearVal,
                       float farVal,
                       mat4 dest);

void
glmc_persp_move_far_lh_no(mat4 proj, float deltaFar);

void
glmc_persp_decomp_lh_no(mat4 proj,
                        float * __restrict nearZ, float * __restrict farZ,
                        float * __restrict top,   float * __restrict bottom,
                        float * __restrict left,  float * __restrict right);

void
glmc_persp_decompv_lh_no(mat4 proj, float dest[6]);

void
glmc_persp_decomp_x_lh_no(mat4 proj,
                          float * __restrict left,
                          float * __restrict right);

void
glmc_persp_decomp_y_lh_no(mat4 proj,
                          float * __restrict top,
                          float * __restrict bottom);

void
glmc_persp_decomp_z_lh_no(mat4 proj,
                          float * __restrict nearZ,
                          float * __restrict farZ);

void
glmc_persp_decomp_far_lh_no(mat4 proj, float * __restrict farZ);

void
glmc_persp_decomp_near_lh_no(mat4 proj, float * __restrict nearZ);

void
glmc_persp_sizes_lh_no(mat4 proj, float fovy, vec4 dest);

float
glmc_persp_fovy_lh_no(mat4 proj);

float
glmc_persp_aspect_lh_no(mat4 proj);


// clipspace/persp_lh_zo.h----------------------------------------------------------------------------------------------

void
glmc_frustum_lh_zo(float left,    float right,
                   float bottom,  float top,
                   float nearZ,   float farZ,
                   mat4  dest);

void
glmc_perspective_lh_zo(float fovy,
                       float aspect,
                       float nearVal,
                       float farVal,
                       mat4 dest);

void
glmc_persp_move_far_lh_zo(mat4 proj, float deltaFar);

void
glmc_persp_decomp_lh_zo(mat4 proj,
                        float * __restrict nearZ, float * __restrict farZ,
                        float * __restrict top,   float * __restrict bottom,
                        float * __restrict left,  float * __restrict right);

void
glmc_persp_decompv_lh_zo(mat4 proj, float dest[6]);

void
glmc_persp_decomp_x_lh_zo(mat4 proj,
                          float * __restrict left,
                          float * __restrict right);

void
glmc_persp_decomp_y_lh_zo(mat4 proj,
                          float * __restrict top,
                          float * __restrict bottom);

void
glmc_persp_decomp_z_lh_zo(mat4 proj,
                          float * __restrict nearZ,
                          float * __restrict farZ);

void
glmc_persp_decomp_far_lh_zo(mat4 proj, float * __restrict farZ);

void
glmc_persp_decomp_near_lh_zo(mat4 proj, float * __restrict nearZ);

void
glmc_persp_sizes_lh_zo(mat4 proj, float fovy, vec4 dest);

float
glmc_persp_fovy_lh_zo(mat4 proj);

float
glmc_persp_aspect_lh_zo(mat4 proj);


// clipspace/persp_rh_no.h----------------------------------------------------------------------------------------------


void
glmc_frustum_rh_no(float left,    float right,
                   float bottom,  float top,
                   float nearZ,   float farZ,
                   mat4  dest);

void
glmc_perspective_rh_no(float fovy,
                       float aspect,
                       float nearVal,
                       float farVal,
                       mat4 dest);

void
glmc_persp_move_far_rh_no(mat4 proj, float deltaFar);

void
glmc_persp_decomp_rh_no(mat4 proj,
                        float * __restrict nearZ, float * __restrict farZ,
                        float * __restrict top,   float * __restrict bottom,
                        float * __restrict left,  float * __restrict right);

void
glmc_persp_decompv_rh_no(mat4 proj, float dest[6]);

void
glmc_persp_decomp_x_rh_no(mat4 proj,
                          float * __restrict left,
                          float * __restrict right);

void
glmc_persp_decomp_y_rh_no(mat4 proj,
                          float * __restrict top,
                          float * __restrict bottom);

void
glmc_persp_decomp_z_rh_no(mat4 proj,
                          float * __restrict nearZ,
                          float * __restrict farZ);

void
glmc_persp_decomp_far_rh_no(mat4 proj, float * __restrict farZ);

void
glmc_persp_decomp_near_rh_no(mat4 proj, float * __restrict nearZ);

void
glmc_persp_sizes_rh_no(mat4 proj, float fovy, vec4 dest);

float
glmc_persp_fovy_rh_no(mat4 proj);

float
glmc_persp_aspect_rh_no(mat4 proj);


// clipspace/persp_rh_zo.h----------------------------------------------------------------------------------------------

void
glmc_frustum_rh_zo(float left,    float right,
                   float bottom,  float top,
                   float nearZ,   float farZ,
                   mat4  dest);

void
glmc_perspective_rh_zo(float fovy,
                       float aspect,
                       float nearVal,
                       float farVal,
                       mat4 dest);

void
glmc_persp_move_far_rh_zo(mat4 proj, float deltaFar);

void
glmc_persp_decomp_rh_zo(mat4 proj,
                        float * __restrict nearZ, float * __restrict farZ,
                        float * __restrict top,   float * __restrict bottom,
                        float * __restrict left,  float * __restrict right);

void
glmc_persp_decompv_rh_zo(mat4 proj, float dest[6]);

void
glmc_persp_decomp_x_rh_zo(mat4 proj,
                          float * __restrict left,
                          float * __restrict right);

void
glmc_persp_decomp_y_rh_zo(mat4 proj,
                          float * __restrict top,
                          float * __restrict bottom);

void
glmc_persp_decomp_z_rh_zo(mat4 proj,
                          float * __restrict nearZ,
                          float * __restrict farZ);

void
glmc_persp_decomp_far_rh_zo(mat4 proj, float * __restrict farZ);

void
glmc_persp_decomp_near_rh_zo(mat4 proj, float * __restrict nearZ);

void
glmc_persp_sizes_rh_zo(mat4 proj, float fovy, vec4 dest);

float
glmc_persp_fovy_rh_zo(mat4 proj);

float
glmc_persp_aspect_rh_zo(mat4 proj);


// clipspace/view_lh_no.h----------------------------------------------------------------------------------------------

void
glmc_lookat_lh_no(vec3 eye, vec3 center, vec3 up, mat4 dest);

void
glmc_look_lh_no(vec3 eye, vec3 dir, vec3 up, mat4 dest);

void
glmc_look_anyup_lh_no(vec3 eye, vec3 dir, mat4 dest);


// clipspace/view_lh_zo.h----------------------------------------------------------------------------------------------

void
glmc_lookat_lh_zo(vec3 eye, vec3 center, vec3 up, mat4 dest);

void
glmc_look_lh_zo(vec3 eye, vec3 dir, vec3 up, mat4 dest);

void
glmc_look_anyup_lh_zo(vec3 eye, vec3 dir, mat4 dest);


// clipspace/view_rh_no.h----------------------------------------------------------------------------------------------

void
glmc_lookat_rh_no(vec3 eye, vec3 center, vec3 up, mat4 dest);

void
glmc_look_rh_no(vec3 eye, vec3 dir, vec3 up, mat4 dest);

void
glmc_look_anyup_rh_no(vec3 eye, vec3 dir, mat4 dest);


// clipspace/view_rh_zo.h----------------------------------------------------------------------------------------------

void
glmc_lookat_rh_zo(vec3 eye, vec3 center, vec3 up, mat4 dest);

void
glmc_look_rh_zo(vec3 eye, vec3 dir, vec3 up, mat4 dest);

void
glmc_look_anyup_rh_zo(vec3 eye, vec3 dir, mat4 dest);


// EOF
