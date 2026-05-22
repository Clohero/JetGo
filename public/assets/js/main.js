const weightInput = document.getElementById('weight')
const costDisplay = document.getElementById('cost_display')
const costVal = document.getElementById('cost_val')

const rates = { standard: 150, express: 280, premium: 550 }

function calcCost() {
    const weight = parseFloat(weightInput.value) || 1
    const type = document.querySelector('input[name="delivery_type"]:checked').value
    const cost = Math.round(rates[type] * weight + rates[type] * 0.8)
    costDisplay.textContent = cost + ' ₽'
    costVal.value = cost
}

function selectCard(card) {
    document.querySelectorAll('.delivery-card').forEach(function(c) {
        c.classList.remove('selected')
    })
    card.classList.add('selected')
    calcCost()
}

weightInput.addEventListener('input', calcCost)

document.querySelectorAll('.delivery-card').forEach(function(card) {
    card.addEventListener('click', function() {
        selectCard(card)
    })
})

calcCost()


function loadPvz(citySelect, pvzSelect) {
    var id_city = citySelect.value
    pvzSelect.innerHTML = '<option value="">загрузка...</option>'
    fetch('/src/orders/get-pvz.php?id_city=' + id_city)
        .then(function(r) { return r.json() })
        .then(function(data) {
            pvzSelect.innerHTML = '<option value="">выберите ПВЗ</option>'
            data.forEach(function(pvz) {
                pvzSelect.innerHTML += '<option value="' + pvz.id_pvz + '">' + pvz.address + '</option>'
            })
        })
}

var senderCity = document.getElementById('sender_city')
var senderPvz = document.getElementById('sender_pvz')
var recipientCity = document.getElementById('recipient_city')
var recipientPvz = document.getElementById('recipient_pvz')

if (senderCity) {
    senderCity.addEventListener('change', function() {
        loadPvz(senderCity, senderPvz)
    })
    recipientCity.addEventListener('change', function() {
        loadPvz(recipientCity, recipientPvz)
    })
}