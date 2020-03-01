import {getFilm} from '../../apis/booking/bookingApi'
export default {
    state: {
        film: {},
        seats: {},
        selectedSeats: [],
        holdSeats: [],
        selectedCombo: {},
        allPackages: {},
        paymentMethod: 'momo'
    },
    getters: {
        film: function (state) {
            return state.film;
        },
        holdSeats: state => state.holdSeats,
        selectedSeats: function (state) {
            return state.selectedSeats;
        },
        paymentMethod: state => state.paymentMethod,
        selectedCombo: state => state.selectedCombo,
        getSelectedSeats: state => state.selectedSeats.map(seat => seat.seat.row + seat.seat.number),
        getHoldSeats: state => state.holdSeats.map(seat => seat.seat.row + seat.seat.number),
        getSelectedSeatsId: state => state.selectedSeats.map(seat => parseInt(seat.seat.id)),
        getHoldSeatsId: state => state.holdSeats.map(seat => parseInt(seat.seat.id)),
        selectedNormal: state => state.selectedSeats.filter(seat => seat.seat.type === 0),
        selectedVIP: state => state.selectedSeats.filter(seat => seat.seat.type === 1),
        selectedDouble: state => state.selectedSeats.filter(seat => seat.seat.type === 2),
        totalAmount: function (state) {
            let total = 0,
                seatAmounts = state.selectedSeats.map(seat => {
                    return seat.seat.type === 0 ? 45000 : (seat.seat.type === 1 ? 55000 : 90000)
                });

            seatAmounts.forEach(function (amount) {
                total += amount
            });

            return total;

        },
        allPackages: state => state.allPackages,
    },
    mutations: {
        setHoldSeat (state, seat) {
            state.holdSeats.push(seat);
        },
        setFilm(state, film) {
            state.film = film;
        },
        setSeat(state, seat) {
            state.selectedSeats.push(seat);
        },
        deleteHoldSeat (state, seat) {
            state.holdSeats = state.holdSeats.filter(function (item) {
                return item.seat.id !== seat.seat.id && item.user.id === seat.user.id;
            });
        },
        deleteSeat(state, seat) {
            state.selectedSeats = state.selectedSeats.filter(function (item) {
                return item.seat.id !== seat.seat.id && item.user.id === seat.user.id;
            });
        },
        setCombo(state, combo) {
            if (state.selectedCombo.id !== combo.id) {
                state.selectedCombo = combo;
            }
        },
        deleteCombo(state, combo) {
            if (state.selectedCombo.id === combo.id) {
                state.selectedCombo = {}
            }
        },
        setPackages(state, time) {
            let seats = [];
            state.selectedSeats.forEach(function (seat) {
                let newSeat = {seat: {}};
                newSeat['seat']['id'] = seat.seat.id;
                newSeat['seat']['type'] = seat.seat.type;
                seats.push(newSeat);
            });
            state.allPackages = {
                time: {'id': time.id},
                seats: seats,
                combo: {'id': state.selectedCombo.id},
                totalPrice: this.getters.totalAmount + state.selectedCombo.price || this.getters.totalAmount,
                paymentMethod: state.paymentMethod
            };
        },
        setPaymentMethod(state, method) {
            state.paymentMethod = method;
        }
    },
    actions: {
        async setFilm({commit}, filmId) {
            let res = await getFilm(filmId);
            return commit('setFilm', res.data);
        },
        setHoldSeat ({commit}, seats) {
            let self = this;
            seats.forEach(function (seat) {
                if (!self.state.holdSeats.some(holdSeat => holdSeat.seat.id === seat.seat.id && holdSeat.user.id === seat.user.id)) {
                    return commit('setHoldSeat', seat);
                }
                return commit('deleteHoldSeat', seat);
            });
        },
        destroyHoldSeats ({commit}, user) {
            this.state.holdSeats.forEach(function (seat) {
                if (parseInt(seat.user.id) === user.id) {
                    commit('deleteHoldSeat', seat);
                }
            });
        },
        setSeats ({commit}, seat) {
            if (!this.state.selectedSeats.some(selectedSeat => selectedSeat.seat.id === seat.seat.id && selectedSeat.user.id === seat.user.id)) {
                return commit('setSeat', seat);
            }
            return commit('deleteSeat', seat);
        },
        setCombo({commit}, combo) {
            return commit('setCombo', combo);
        },
        deleteCombo({commit}, combo) {
            return commit('deleteCombo', combo);
        },
        setPaymentMethod({commit}, method) {
            return commit('setPaymentMethod', method);
        },
        setPackages({commit}, time) {
            return commit('setPackages', time);
        }
    }
}
