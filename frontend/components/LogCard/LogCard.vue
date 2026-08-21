<template>
    <view class="log-info">
        <view class="log-info-item">
            <text class="item-left">巡视时间</text>
            <text class="item-right">{{ task.date }}</text>
        </view>
        <view class="log-info-item">
            <text class="item-left">当日天气</text>
            <text class="item-right">{{ task.weather }}</text>
        </view>
        <view class="log-info-item">
            <text class="item-left">巡视人员</text>
            <text class="item-right">{{ task.inspectors.join('、') }}</text>
        </view>
        <view class="log-info-item">
            <text class="item-left">使用车辆</text>
            <text class="item-right">{{ task.car }}</text>
        </view>
        <view class="log-info-item">
            <text class="item-left">巡视记录</text>
        </view>
        <view class="log-info-table">
            <table class="log-table">
                <thead>
                    <tr class="table-header-row">
                        <th>发现时间</th>
                        <th>区域</th>
                        <th>鸟种</th>
                        <th>数量</th>
                        <th>飞行高度</th>
                    </tr>
                </thead>
                <tbody>
					<tr v-for="record in task.records" :key="record.id" class="table-body-row">
						<td>{{ record.time.slice(0, 5) }}</td>
						<td>{{ record.area }}</td>
						<td>{{ record.species }}</td>
						<td>{{ record.minCount }} ~ {{ record.maxCount }}只</td>
						<td>{{ record.minHeight }} ~ {{ record.maxHeight }}m</td>
					</tr>
					<tr v-if="!task.records.length" class="table-body-row"><td colspan="5">暂无巡视记录</td></tr>
                </tbody>
            </table>
        </view>
    </view>
</template>

<script>
export default {
	props: { task: { type: Object, required: true } },
    data() {
        return {

        };
    },
    methods: {
    }
}
</script>

<style lang="scss" scoped>
.log-info {
    display: flex;
    flex-direction: column;
    gap: 16rpx;
    width: 710rpx;
    padding: 30rpx 32rpx 56rpx 32rpx;
    background-color: #fff;
    margin: 0 auto;
    border-radius: 12rpx;

    .log-info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;

        .item-left {
            color: #444;
            font-size: $font28;
        }

        .item-right {
            color: $text-color;
            font-size: $font28;
        }
    }


    .log-info-table {
        width: 646rpx;
    }

    .log-table {
        width: 100%;
        min-width: 100%;
        border-collapse: collapse;
    }

    .table-header-row {
        height: 56rpx;
        background-color: $secondary-color;

        th {
            text-align: center;
            font-size: 28rpx;
            border: 1rpx solid #eee;
            font-weight: normal;
            color: $text-color;
        }
    }

    .table-body-row {
        height: 60rpx;
        background-color: #f8f8f8;

        td {
            text-align: center;
            font-size: 27rpx;
            color: #666;
            border: 1rpx solid #eee;

            &:nth-child(3) {
                color: $text-color;
            }
        }

    }
}
</style>
