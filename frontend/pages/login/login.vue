<template>
	<bg>
		<view class="login">
			<image src="/static/sys-white.png" class="title-image"></image>
			<view class="login-title">用户登陆</view>
			<uv-form labelPosition="left" ref="form">
				<uv-form-item prop="username">
					<uv-input clearable v-model="form.username" fontSize="28rpx" placeholder="请输入账号" prefixIcon="account" border="none"></uv-input>
				</uv-form-item>
				<uv-form-item prop="password">
					<uv-input clearable v-model="form.password" fontSize="28rpx" placeholder="请输入密码" prefixIcon="lock" type="password" border="none"></uv-input>
				</uv-form-item>
				<uv-form-item>
					<uv-input clearable v-model="form.verify" fontSize="28rpx" placeholder="请输入验证码" prefixIcon="empty-permission" border="none" class="verify-code-input" maxlength="4"></uv-input>
					<image :src="verifyImg" @click="getVerify" class="verify-code-image"></image>
				</uv-form-item>
				<view class="forget">忘记密码？</view>
				<uv-form-item>
					<uv-button :disabled="!isSubmit" @click="login" shape="circle" class="login-btn" text="登陆"></uv-button>
				</uv-form-item>
			</uv-form>
			<view class="agreement">
				<view class="agreement-title">
					<view class="agreement-title-line"></view>
					<text>登陆前请先阅读协议</text>
					<view class="agreement-title-line"></view>
				</view>
				<uv-checkbox-group v-model="isAgreen">
					<uv-checkbox shape="square" name="1">
						<view class="agreement-text">
							<text>我已阅读并同意</text>
							<text class="agreement-text-right">《用户协议》</text>
						</view>
					</uv-checkbox>
				</uv-checkbox-group>
			</view>
		</view>
	</bg>
</template>

<script>
import {login} from '/common/api.js'
export default {
	data() {
		return {
			verifyImg:'',
			isAgreen: [],
			form:{
				username:'',
				password:'',
				verify:'',
				virifyToken:'',
			}
		};
	},
	methods: {
		getVerify(){
			uni.request({
				url: login.verify
			})
			.then(res=>{
				if (res.data.code !== 1) {
					uni.showToast({ title: res.data.msg || '验证码加载失败', icon: 'none' })
					return
				}
				this.verifyImg = res.data.data.img
				this.form.virifyToken = res.data.data.token
			},()=>{
				uni.showToast({ title: '无法连接后端服务', icon: 'none' })
			})
		},
		login(){
			if (!this.isSubmit) {
				uni.showToast({ title: '请填写完整并同意用户协议', icon: 'none' })
				return
			}
			uni.showLoading({
				title: '登陆中'
			})
			uni.request({
				url: login.login,
				method: 'POST',
				data: { ...this.form, verify: this.form.verify.toLowerCase() }
			})
			.then(res=>{
				uni.hideLoading();
				if(res.data.code !== 1){
					uni.showModal({
						title:'提示',
						content: res.data.msg || '登录失败',
						showCancel:false
					})
					this.form.verify = ''
					this.getVerify()
					return;
				}
				uni.setStorageSync('user-token', res.data.data.token)
				uni.setStorageSync('user-info', res.data.data)
				uni.switchTab({ url: '/pages/tabbar/index/index' })
			},()=>{
				uni.hideLoading()
				uni.showToast({ title: '无法连接后端服务', icon: 'none' })
			})
			
		}
	},
	computed:{
		isSubmit(){
			return this.form.username && this.form.password && this.form.verify && this.isAgreen.length > 0
		}
	},
	// 页面生命周期 
	// 页面加载完毕后，只调用一次
	onLoad(){
		this.getVerify()
	},
}
</script>

<style lang="scss" scoped>
.header {
	display: flex;
	align-items: center;
	width: 100%;
	height: 88rpx;
	padding-left: 23rpx;

	.header-text {
		font-size: $font36;
		color: $text-color;
		margin-left: 20rpx;
	}
}

.title-image {
	display: block;
	width: 266rpx;
	height: 66rpx;
	margin: 0 auto 98rpx;
	padding-top: 60rpx;
}

.login-title {
	font-size: $font34;
	color: $text-color;
	font-weight: bold;
	margin-left: 76rpx;
	margin-bottom: 46rpx;
}

::v-deep .uv-form-item__body {
	padding-top: 0;
}

::v-deep .uv-form-item {
	.uv-input__content {
		background-color: #fff;
		height: 96rpx;
		margin-left: 60rpx;
		margin-right: 60rpx;
		padding-left: 28rpx;
		font-size: $font28;
		.uv-input__content__field-wrapper__field{
			height: 96rpx;
			line-height: 96rpx;
			margin-right: 30rpx;
		}
	}
}

::v-deep .verify-code-input {
	flex: 0 0 436rpx !important;

	.uv-input__content {
		width: 100% !important;
	}
}

.verify-code-image {
	width: 250rpx;
	height: 96rpx;
}

.forget {
	width: 140rpx;
	height: 40rpx;
	font-size: $font28;
	color: #fff;
	margin: 14rpx 60rpx 104rpx auto;
}

.login-btn {
	width: 500rpx;
	height: 76rpx;
	margin: 0 auto;
}

::v-deep .login-btn {
	.uv-button {
		color: $main-color;
		font-size: $font28;
		font-weight: bold;
	}
}

.agreement {
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	gap: 38rpx;
	margin-top: 154rpx;
	font-size: $font26;

	.agreement-title {
		display: flex;
		align-items: center;
		gap: 10rpx;
		color: #fff;

		.agreement-title-line {
			width: 66rpx;
			height: 2rpx;
			background-color: #fff;
			font-size: $font22;
		}
	}

	.agreement-text {
		display: flex;
		align-items: center;

		.agreement-text-right {
			color: #fff;
		}
	}
}
</style>
