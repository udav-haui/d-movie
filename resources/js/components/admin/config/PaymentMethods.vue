<template>
    <div>
        <div class="col-lg-12 text-right m-b-10">
            <button @click="save" class="btn dmovie-btn dmovie-btn-success dmovie-btn-large">
                <i class="fa fa-save" aria-hidden="true"></i> &nbsp;
                {{ __('Save') }}
            </button>
        </div>
        <ul class="col-lg-12 accordion">
            <li class="accordion-list__item">
                <div class="accordion-item false">
                    <div class="accordion-item__line">
                        <a href="#" class="accordion-item__title">
                            <span>{{ __('Momo Payment Method') }}</span>
                        </a>
                        <span class="accordion-item__icon"></span>
                    </div>
                    <div class="accordion-item__inner">
                        <div class="accordion-item__content">
                            <div class="accordion-item__paragraph">
                                <div class="row">
                                    <div class="col-lg-12 form-horizontal">
                                        <div class="form-group">
                                            <label for="momo_partner_code" class="col-sm-3 control-label">Partner Code</label>
                                            <div class="col-sm-9">
                                                <input type="text" v-model="momoConfig.MOMO_PARTNER_CODE" class="form-control" id="momo_partner_code" :placeholder="partnerCodePlaceHolderTextHint">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="momo_access_key" class="col-sm-3 control-label">Access Key</label>
                                            <div class="col-sm-9">
                                                <input type="text" v-model="momoConfig.MOMO_ACCESS_KEY" class="form-control" id="momo_access_key" :placeholder="accessKeyPlaceHolderTextHint">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="momo_secret_key" class="col-sm-3 control-label">Secret Key</label>
                                            <div class="col-sm-9">
                                                <input type="password" v-model="momoConfig.MOMO_SECRET_KEY" class="form-control" id="momo_secret_key" :placeholder="secretKeyPlaceHolderTextHint">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="momo_end_point" class="col-sm-3 control-label">End Point</label>
                                            <div class="col-sm-9">
                                                <input type="text" v-model="momoConfig.MOMO_ENDPOINT" class="form-control" id="momo_end_point" :placeholder="endPointPlaceHolderTextHint">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
    import { getPaymentMethodsConfig, savePaymentMethodsConfig } from '../../../store/apis/store_config/store_config_api'
    export default {
        mounted: function() {
            console.log("mounted");
            let response = getPaymentMethodsConfig(), that = this;
            response.then(function (res) {
                if (res.status === 200) {
                    that.momoConfig = res.data.data.momo;
                }
            })
        },
        props: [
            'partnerCodePlaceHolderTextHint',
            'accessKeyPlaceHolderTextHint',
            'secretKeyPlaceHolderTextHint',
            'endPointPlaceHolderTextHint'
        ],
        data: function() {
            return {
                momoConfig: {}
            }
        },
        methods: {
            save: function () {
                let data = {
                    'momo': this.momoConfig
                };
                let response = savePaymentMethodsConfig(data);
                response.then(function (res) {
                    if (res.status === 200) {
                        swalTopRightAlert(res.data.messages);
                    }else {
                        normalAlert(res.data ? res.data.messages: res.message);
                    }
                }).catch(function (error) {
                    console.log(error);
                })
            }
        }
    }
</script>
