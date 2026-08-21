<template>
	<view class="addtask">
		<view class="header">
			<uv-icon name="arrow-left" size="36rpx" color="#fff" @click="goBack"></uv-icon>
			<text class="header-text">新增场内巡视计划</text>
			<MoreBtn class="more-btn"></MoreBtn>
		</view>
		<uv-form>
			<view class="task-form">
				<uv-form-item borderBottom>
					<view class="form-item-date">
						<text>巡视日期</text>
						<text>{{ stampToDateTime(new Date().getTime()).date }}</text>
					</view>
				</uv-form-item>
				<uv-form-item borderBottom>
					<view class="form-item-inspector">
						<view class="form-item-inspector-text">
							<text>巡视人员</text>
							<text>请选择</text>
						</view>
						<view class="form-item-inspector-checkbox">
							<uv-checkbox-group v-model="selectedInspectors" placement="row">
								<uv-checkbox v-for="(item, index) in inspectorList" :key="index" :name="String(item.id)"
									:label="item.name" :customStyle="{ marginRight: '28rpx' }"
									size="30rpx"></uv-checkbox>
							</uv-checkbox-group>
						</view>
					</view>
				</uv-form-item>
				<uv-form-item borderBottom>
					<view class="form-item-car">
						<text>车辆</text>
						<view class="form-item-car-select" @click="open('car')">
							<text :class="selectCar == '请选择' ? '' : 'selected'">{{ selectCar }}</text>
							<uv-icon name="arrow-right"></uv-icon>
						</view>
					</view>
					<uv-picker ref="carPicker" :columns="carList" @confirm="carConfirm"></uv-picker>
				</uv-form-item>
				<uv-form-item borderBottom>
					<view class="form-item-weather">
						<text>天气</text>
						<view class="form-item-weather-select" @click="open('weather')">
							<text :class="selectWeather == '请选择' ? '' : 'selected'">{{ selectWeather }}</text>
							<uv-icon name="arrow-right"></uv-icon>
						</view>
					</view>
					<uv-picker ref="weatherPicker" :columns="weatherList" @confirm="weatherConfirm"></uv-picker>
				</uv-form-item>
			</view>
			<view class="task-submit-btn">
				<uv-button text="保存，开始巡视" shape="circle" @click="submit"
					:customStyle="{ color: '#4B9EFF', fontWeight: 'bold' }"></uv-button>
			</view>

		</uv-form>
	</view>
</template>

<script>
import MoreBtn from '@/components/MoreBtn/MoreBtn.vue'
import { stampToDateTime } from '@/utils/dateFormat.js'
import { addTask, getAdmins, getCars } from '@/utils/inspectionApi.js'
export default {
	components: {
		MoreBtn
	},
	data() {
		return {
			selectedInspectors: [],
			inspectorList: [],
			carOptions: [],
			carList: [[]],
			weatherList: [
				['晴', '阴', '雨', '雪']
			],
			selectCar: '请选择',
			selectCarId: 0,
			selectWeather: '请选择'
		};
	},
	async onLoad() {
		try {
			const [admins, cars] = await Promise.all([getAdmins(), getCars()])
			this.inspectorList = admins.map(item => ({ id: item.id, name: item.nickname }))
			this.carOptions = cars.map(item => ({ ...item, label: `${item.name} ${item.code}` }))
			this.carList = [this.carOptions.map(item => item.label)]
		} catch (e) {}
	},
	methods: {
		stampToDateTime,
		goBack() {
			const pages = getCurrentPages();
			if (pages.length > 1) {
				uni.navigateBack();
			} else {
				uni.switchTab({
					url: '/pages/tabbar/index/index'
				});
			}
		},
		open(type) {
			if (type === 'car') {
				this.$refs.carPicker.open();
			} else if (type === 'weather') {
				this.$refs.weatherPicker.open();
			}
		},
		carConfirm(e) {
			this.selectCar = e.value[0];
			const car = this.carOptions.find(item => item.label === this.selectCar)
			this.selectCarId = car ? car.id : 0
		},
		weatherConfirm(e) {
			this.selectWeather = e.value[0];
		},
		async submit() {
			if (!this.selectedInspectors.length || !this.selectCarId || this.selectWeather === '请选择') {
				uni.showToast({ title: '请完整填写巡视计划', icon: 'none' })
				return
			}
			try {
				await addTask({ date: stampToDateTime(Date.now()).date, admin_ids: this.selectedInspectors, car_id: this.selectCarId, weather: this.selectWeather })
				uni.showToast({ title: '任务提交成功', icon: 'success' })
				setTimeout(() => uni.switchTab({ url: '/pages/tabbar/index/index' }), 400)
			} catch (e) {}
		}
	}
}
</script>

<style lang="scss" scoped>
.header {
	width: 100%;
	height: 88rpx;
	display: flex;
	align-items: center;
	padding-left: 14rpx;
	margin-bottom: 14rpx;

	.header-text {
		color: #fff;
		font-size: $font36;
		font-weight: blod;
		margin-left: 10rpx;
	}

	.more-btn {
		margin-left: auto;
		margin-right: 12rpx;
	}
}

.task-form {
	width: 690rpx;
	background-color: #fff;
	margin: 0 auto;
	border-radius: 10rpx;

	.form-item-date {
		width: 100%;
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin: 10rpx 54rpx 0 60rpx;

		text:nth-child(1) {
			color: #222;
			font-size: $font28;
		}

		text:nth-child(2) {
			color: #000;
			font-size: $font28;
		}
	}

	.form-item-inspector {
		width: 100%;

		.form-item-inspector-text {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin: 0 54rpx 28rpx 42rpx;

			text:nth-child(1) {
				color: #222;
				font-size: $font28;

				&::before {
					content: "*";
					color: #ee4444;
					margin-right: 8rpx;
				}
			}

			text:nth-child(2) {
				color: #999;
				font-size: $font28;
			}
		}

		.form-item-inspector-checkbox {
			margin-left: 48rpx;
		}
	}

	.form-item-car,
	.form-item-weather {
		width: 100%;
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-left: 42rpx;
		margin-right: 40rpx;

		> :first-child {
			color: #222;
			font-size: $font28;

			&::before {
				content: "*";
				color: #ee4444;
				margin-right: 8rpx;
			}
		}

		.form-item-car-select,
		.form-item-weather-select {
			display: flex;
			gap: 20rpx;
			align-items: center;
			color: #999;
			font-size: $font28;
		}
	}

	.form-item-weather {
		margin-bottom: 12rpx;
	}
}

::v-deep .uv-form-item__body {
	padding: 28rpx 0;
}

::v-deep .uv-line {
	width: 610rpx !important;
	margin: 0 auto !important;
}

.task-submit-btn {
	width: 500rpx;
	height: 76rpx;
	position: fixed;
	left: 50%;
	transform: translateX(-50%);
	bottom: 150rpx;
}

.selected {
	color: #000 !important;
}
</style>
