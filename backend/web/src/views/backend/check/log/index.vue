<template>
    <div class="default-main ba-table-box">
        <el-alert class="ba-table-alert" v-if="baTable.table.remark" :title="baTable.table.remark" type="info" show-icon />

        <!-- 表格顶部菜单 -->
        <TableHeader
            :buttons="['refresh', 'add', 'edit', 'delete', 'comSearch', 'quickSearch', 'columnDisplay']"
            :quick-search-placeholder="t('quick Search Placeholder', { fields: t('check.log.quick Search Fields') })"
        />

        <!-- 表格 -->
        <!-- 要使用`el-table`组件原有的属性，直接加在Table标签上即可 -->
        <Table ref="tableRef" />

        <!-- 表单 -->
        <PopupForm />
    </div>
</template>

<script setup lang="ts">
import { ref, provide, onMounted } from 'vue'
import baTableClass from '/@/utils/baTable'
import { defaultOptButtons } from '/@/components/table'
import { baTableApi } from '/@/api/common'
import { useI18n } from 'vue-i18n'
import PopupForm from './popupForm.vue'
import Table from '/@/components/table/index.vue'
import TableHeader from '/@/components/table/header/index.vue'

const { t } = useI18n()
const tableRef = ref()
const optButtons = defaultOptButtons(['edit', 'delete'])
const baTable = new baTableClass(
    new baTableApi('/admin/check.Log/'),
    {
        pk: 'id',
        column: [
            { type: 'selection', align: 'center', operator: false },
            { label: t('check.log.id'), prop: 'id', align: 'center', width: 70, operator: 'RANGE', sortable: 'custom' },
            { label: t('check.log.check_plan__date'), prop: 'checkPlan.date', align: 'center', operatorPlaceholder: t('Fuzzy query'), render: 'tags', operator: 'LIKE' },
            { label: t('check.log.time'), prop: 'time', align: 'center', operator: '=', sortable: 'custom' },
            { label: t('check.log.area'), prop: 'area', align: 'center', render: 'tag', operator: '=', sortable: false, replaceValue: { A: t('check.log.area A'), B: t('check.log.area B'), C: t('check.log.area C'), D: t('check.log.area D') } },
            { label: t('check.log.bird__name'), prop: 'bird.name', align: 'center', operatorPlaceholder: t('Fuzzy query'), render: 'tags', operator: 'LIKE' },
            { label: t('check.log.min_num'), prop: 'min_num', align: 'center', operator: 'RANGE', sortable: false },
            { label: t('check.log.max_num'), prop: 'max_num', align: 'center', operator: 'RANGE', sortable: false },
            { label: t('check.log.min_height'), prop: 'min_height', align: 'center', operator: 'RANGE', sortable: false },
            { label: t('check.log.max_height'), prop: 'max_height', align: 'center', operator: 'RANGE', sortable: false },
            { label: t('check.log.active_type'), prop: 'active_type', align: 'center', render: 'tags', operator: 'FIND_IN_SET', sortable: false, replaceValue: { 0: t('check.log.active_type 0'), 1: t('check.log.active_type 1'), 2: t('check.log.active_type 2'), 3: t('check.log.active_type 3') } },
            { label: t('check.log.cuoshi'), prop: 'cuoshi', align: 'center', render: 'tags', operator: 'FIND_IN_SET', sortable: false, replaceValue: { 0: t('check.log.cuoshi 0'), 1: t('check.log.cuoshi 1') } },
            { label: t('check.log.result'), prop: 'result', align: 'center', render: 'tag', operator: '=', sortable: false, replaceValue: { A: t('check.log.result A'), B: t('check.log.result B'), C: t('check.log.result C'), D: t('check.log.result D') } },
            { label: t('operate'), align: 'center', width: 100, render: 'buttons', buttons: optButtons, operator: false },
        ],
        dblClickNotEditColumn: [undefined],
    },
    {
        defaultItems: { time: null, area: 'A', min_num: 0, max_num: 0, min_height: 0, max_height: 0, result: 'A' },
    }
)

provide('baTable', baTable)

onMounted(() => {
    baTable.table.ref = tableRef.value
    baTable.mount()
    baTable.getIndex()?.then(() => {
        baTable.initSort()
        baTable.dragSort()
    })
})
</script>

<script lang="ts">
import { defineComponent } from 'vue'
export default defineComponent({
    name: 'check/log',
})
</script>

<style scoped lang="scss"></style>
