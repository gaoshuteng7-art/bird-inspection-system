export const host = "http://localhost:8000";

export const login = {
	verify: host + "/appapi/Index/captcha",
	login: host + "/appapi/Index/login",
}

export const inspection = {
	plans: host + '/appapi/check.Plan/index',
	addPlan: host + '/appapi/check.Plan/add',
	addLog: host + '/appapi/check.Log/add',
	admins: host + '/appapi/Admin/index',
	cars: host + '/appapi/Car/index',
	birds: host + '/appapi/Bird/index'
}
