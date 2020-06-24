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
                                                <input type="text" v-model="momoConfig.partnerCode" class="form-control" id="momo_partner_code" :placeholder="partnerCodePlaceHolderTextHint">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="momo_access_key" class="col-sm-3 control-label">Access Key</label>
                                            <div class="col-sm-9">
                                                <input type="text" v-model="momoConfig.accessKey" class="form-control" id="momo_access_key" :placeholder="accessKeyPlaceHolderTextHint">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="momo_secret_key" class="col-sm-3 control-label">Secret Key</label>
                                            <div class="col-sm-9">
                                                <input type="password" v-model="momoConfig.secretKey" class="form-control" id="momo_secret_key" :placeholder="secretKeyPlaceHolderTextHint">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="momo_end_point" class="col-sm-3 control-label">End Point</label>
                                            <div class="col-sm-9">
                                                <input type="text" v-model="momoConfig.endPoint" class="form-control" id="momo_end_point" :placeholder="endPointPlaceHolderTextHint">
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
                    // Map key from snake case to camel case
                    let mappedData = _.mapKeys(res.data.data.momo, (v, k) => _.camelCase(k));
                    that.momoConfig = mappedData;
                }
                if (res.status === 403) {
                    errorMessage(res.data ? res.data.message : __('Unauthorized'));
                }
                if (res.status === 400) {
                    errorMessage(res.data ? res.data.message : __('Something went wrong! Please try again.'));
                }
            }).catch(function (error) {
                console.log(error);
            });
        },
        props: [
            'partnerCodePlaceHolderTextHint',
            'accessKeyPlaceHolderTextHint',
            'secretKeyPlaceHolderTextHint',
            'endPointPlaceHolderTextHint'
        ],
        data: function() {
            return {
                momoConfig: {
                    partnerCode: null,
                    accessKey: null,
                    secretKey: null,
                    endPoint: null
                }
            }
        },
        methods: {
            save: function () {
                // map data to snake case key-value
                let mappedData = _.mapKeys(this.momoConfig, (v, k) => _.snakeCase(k)),
                    data = {
                        'momo': mappedData
                    },
                    response = savePaymentMethodsConfig(data);
                response.then(function (res) {
                    if (res.status === 200) {
                        swalTopRightAlert(res.data.message);
                    } else if(res.status === 304) {

                    } else {
                        normalAlert(res.data ? res.data.message: res.message);
                    }
                }).catch(function (error) {
                    console.log(error);
                })
            }
        }
    }
</script>
