export default {
  methods: {
    formatPrice(value, fx, location) {
      if (fx !== null && fx != undefined) {
        const formattedValue = fx(value).from("USD").to(location.currency.code).toFixed(2);
        return location.currency.symbol + formattedValue;
      }
      // return value;
    },
}
}
