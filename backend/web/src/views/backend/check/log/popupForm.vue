<template>
    <!-- 对话框表单 -->
    <el-dialog
        class="ba-operate-dialog"
        :close-on-click-modal="false"
        :model-value="['add', 'edit'].includes(baTable.form.operate!)"
        @close="baTable.toggleForm"
        width="50%"
    >
        <template #header>
            <div class="title" v-drag="['.ba-operate-dialog', '.el-dialog__header']" v-zoom="'.ba-operate-dialog'">
                {{ baTable.form.operate ? t(baTable.form.operate) : '' }}
            </div>
        </template>
        <el-scrollbar v-loading="baTable.form.loading" class="ba-table-form-scrollbar">
            <div
                class="ba-operate-form"
                :class="'ba-' + baTable.form.operate + '-form'"
                :style="'width: calc(100% - ' + baTable.form.labelWidth! / 2 + 'px)'"
            >
                <el-form
                    v-if="!baTable.form.loading"
                    ref="formRef"
                    @submit.prevent=""
                    @keyup.enter="baTable.onSubmit(formRef)"
                    :model="baTable.form.items"
                    label-position="right"
                    :label-width="baTable.form.labelWidth + 'px'"
                    :rules="rules"
                >
                    <FormItem :label="t('check.log.check_plan_id')" type="remoteSelect" v-model="baTable.form.items!.check_plan_id" prop="check_plan_id" :input-attr="{ pk: 'check_plan.id', field: 'date', 'remote-url': '/admin/check.Plan/index' }" :placeholder="t('Please select field', { field: t('check.log.check_plan_id') })" />
                    <FormItem :label="t('check.log.time')" type="time" v-model="baTable.form.items!.time" prop="time" :placeholder="t('Please select field', { field: t('check.log.time') })" />
                    <FormItem :label="t('check.log.area')" type="select" v-model="baTable.form.items!.area" prop="area" :data="{ content: { A: t('check.log.area A'), B: t('check.log.area B'), C: t('check.log.area C'), D: t('check.log.area D') } }" :placeholder="t('Please select field', { field: t('check.log.area') })" />
                    <FormItem :label="t('check.log.bird_id')" type="remoteSelect" v-model="baTable.form.items!.bird_id" prop="bird_id" :input-attr="{ pk: 'bird.id', field: 'name', 'remote-url': '/admin/Bird/index' }" :placeholder="t('Please select field', { field: t('check.log.bird_id') })" />
                    <FormItem :label="t('check.log.min_num')" type="number" prop="min_num" :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.min_num" :placeholder="t('Please input field', { field: t('check.log.min_num') })" />
                    <FormItem :label="t('check.log.max_num')" type="number" prop="max_num" :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.max_num" :placeholder="t('Please input field', { field: t('check.log.max_num') })" />
                    <FormItem :label="t('check.log.min_height')" type="number" prop="min_height" :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.min_height" :placeholder="t('Please input field', { field: t('check.log.min_height') })" />
                    <FormItem :label="t('check.log.max_height')" type="number" prop="max_height" :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.max_height" :placeholder="t('Please input field', { field: t('check.log.max_height') })" />
                    <FormItem :label="t('check.log.active_type')" type="selects" v-model="baTable.form.items!.active_type" prop="active_type" :data="{ content: { 0: t('check.log.active_type 0'), 1: t('check.log.active_type 1'), 2: t('check.log.active_type 2'), 3: t('check.log.active_type 3') } }" :placeholder="t('Please select field', { field: t('check.log.active_type') })" />
                    <FormItem :label="t('check.log.cuoshi')" type="selects" v-model="baTable.form.items!.cuoshi" prop="cuoshi" :data="{ content: { 0: t('check.log.cuoshi 0'), 1: t('check.log.cuoshi 1') } }" :placeholder="t('Please select field', { field: t('check.log.cuoshi') })" />
                    <FormItem :label="t('check.log.result')" type="select" v-model="baTable.form.items!.result" prop="result" :data="{ content: { A: t('check.log.result A'), B: t('check.log.result B'), C: t('check.log.result C'), D: t('check.log.result D') } }" :placeholder="t('Please select field', { field: t('check.log.result') })" />
                </el-form>
            </div>
        </el-scrollbar>
        <template #footer>
            <div :style="'width: calc(100% - ' + baTable.form.labelWidth! / 1.8 + 'px)'">
                <el-button @click="baTable.toggleForm('')">{{ t('Cancel') }}</el-button>
                <el-button v-blur :loading="baTable.form.submitLoading" @click="baTable.onSubmit(formRef)" type="primary">
                    {{ baTable.form.operateIds && baTable.form.operateIds.length > 1 ? t('Save and edit next item') : t('Save') }}
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script setup lang="ts">
import { reactive, ref, inject } from 'vue'
import { useI18n } from 'vue-i18n'
import type baTableClass from '/@/utils/baTable'
import FormItem from '/@/components/formItem/index.vue'
import type { ElForm, FormItemRule } from 'element-plus'
import { buildValidatorData } from '/@/utils/validate'

const formRef = ref<InstanceType<typeof ElForm>>()
const baTable = inject('baTable') as baTableClass

const { t } = useI18n()

const rules: Partial<Record<string, FormItemRule[]>> = reactive({
    min_num: [buildValidatorData({ name: 'number', title: t('check.log.min_num') })],
    max_num: [buildValidatorData({ name: 'number', title: t('check.log.max_num') })],
    min_height: [buildValidatorData({ name: 'number', title: t('check.log.min_height') })],
    max_height: [buildValidatorData({ name: 'number', title: t('check.log.max_height') })],
})
</script>

<style scoped lang="scss"></style>
