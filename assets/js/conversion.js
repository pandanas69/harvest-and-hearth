document.addEventListener('DOMContentLoaded', () => {
  const valueEl = document.getElementById('conv-value');
  const typeEl = document.getElementById('conv-type');
  const resultEl = document.getElementById('conv-result');
  const btnEl = document.getElementById('conv-btn');

  const conversions = {
  cupsToMl: cups => (cups * 240),
  tbspToMl: tbsp => (tbsp * 15),
  tspToMl: tsp => (tsp * 5),
  ozToGrams: oz => (oz * 28.35),
  lbToGrams: lb => (lb * 453.6),
  gToLb: g => (g / 453.6),
  lbToKg: lb => (lb * 0.4536),
  qtToL: qt => (qt * 0.946),
  galToL: gal => (gal * 3.785),
  fToC: f => ((f - 32) * 5 / 9),
  cToF: c => ((c * 9 / 5) + 32)
};


  function format(type, val) {
    switch(type) {
  case 'gToLb': return `${(conversions.gToLb(val)).toFixed(2)} lb`;
  case 'lbToKg': return `${(conversions.lbToKg(val)).toFixed(2)} kg`;
  case 'qtToL': return `${(conversions.qtToL(val)).toFixed(2)} L`;
  case 'galToL': return `${(conversions.galToL(val)).toFixed(2)} L`;
  case 'cups': return `${Math.round(conversions.cupsToMl(val))} mL`;
  case 'tbsp': return `${Math.round(conversions.tbspToMl(val))} mL`;
  case 'tsp':  return `${Math.round(conversions.tspToMl(val))} mL`;
  case 'oz':   return `${Math.round(conversions.ozToGrams(val))} g`;
  case 'lb':   return `${Math.round(conversions.lbToGrams(val))} g`;
  case 'f':    return `${conversions.fToC(val).toFixed(1)} °C`;
  case 'c':    return `${conversions.cToF(val).toFixed(1)} °F`;
}

  }

  function convert() {
    const raw = valueEl.value.trim();
    if (raw === '') {
      resultEl.textContent = 'Enter a value to convert.';
      return;
    }
    const val = parseFloat(raw);
    if (Number.isNaN(val)) {
      resultEl.textContent = 'Please enter a valid number.';
      return;
    }
    const type = typeEl.value;
    resultEl.textContent = format(type, val);
  }

  btnEl.addEventListener('click', convert);
  valueEl.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
      convert();
    }
  });
});

