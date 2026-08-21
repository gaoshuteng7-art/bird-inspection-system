import { inspection } from '@/common/api.js'

function request(url, method = 'GET', data = {}) {
	return new Promise((resolve, reject) => {
		uni.request({
			url,
			method,
			data,
			header: { 'user-token': uni.getStorageSync('user-token') || '' },
			success(res) {
				const body = res.data || {}
				if (body.code === 1) return resolve(body.data)
				uni.showToast({ title: body.msg || '请求失败', icon: 'none' })
				if (body.code === 302) setTimeout(() => uni.navigateTo({ url: '/pages/login/login' }), 500)
				reject(new Error(body.msg || '请求失败'))
			},
			fail(err) {
				uni.showToast({ title: '无法连接后端服务', icon: 'none' })
				reject(err)
			}
		})
	})
}

export const getTasks = (params = {}) => request(inspection.plans, 'GET', params)
export const addTask = data => request(inspection.addPlan, 'POST', data)
export const addRecord = (taskId, data) => request(inspection.addLog, 'POST', { check_plan_id: taskId, ...data })
export const getAdmins = () => request(inspection.admins)
export const getCars = () => request(inspection.cars)
export const getBirds = () => request(inspection.birds)
