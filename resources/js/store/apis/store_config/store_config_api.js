export function getPaymentMethodsConfig() {
    return axios.get(route('api.config.sales.paymentMethods', {}), {})
        .then(res => res)
        .catch(err => {
            console.error(err);
        });
}

export function savePaymentMethodsConfig(data) {
    return axios.put(route('api.config.sales.savePaymentMethods'), {input: data})
        .then(res => res)
        .catch(err => {
            console.error(err);
        });
}
