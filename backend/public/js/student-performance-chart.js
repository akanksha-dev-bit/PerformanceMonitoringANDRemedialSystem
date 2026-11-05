(function () {
  'use strict';

  const gradeTier = (score) => {
    if (score >= 85) return 'Excellent';
    if (score >= 75) return 'Strong';
    if (score >= 60) return 'On track';
    if (score >= 40) return 'Developing';
    return 'Needs support';
  };

  window.createStudentPerformanceChart = (canvas, labels, scores) => {
    if (!canvas || !window.Chart || !Array.isArray(scores) || scores.length === 0) return null;

    const context = canvas.getContext('2d');
    const gradient = context.createLinearGradient(0, 0, 0, canvas.height || 300);
    gradient.addColorStop(0, '#7c6cf3');
    gradient.addColorStop(0.55, '#6658e8');
    gradient.addColorStop(1, '#4f46cf');

    return new Chart(context, {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          label: 'Score',
          data: scores,
          backgroundColor: gradient,
          hoverBackgroundColor: '#8c7bff',
          borderSkipped: false,
          borderRadius: 11,
          maxBarThickness: 48,
          categoryPercentage: 0.66,
          barPercentage: 0.82,
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: window.matchMedia('(prefers-reduced-motion: reduce)').matches
          ? false : { duration: 850, easing: 'easeOutQuart' },
        interaction: { mode: 'nearest', intersect: false },
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: '#18233e',
            padding: 12,
            cornerRadius: 11,
            displayColors: false,
            titleFont: { size: 13, weight: '700' },
            bodyFont: { size: 12, weight: '600' },
            callbacks: {
              label: ({ parsed }) => `${Number(parsed.y).toFixed(1).replace(/\.0$/, '')}% · ${gradeTier(Number(parsed.y))}`,
            },
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            max: 100,
            border: { display: false },
            grid: { color: '#edf0f7', drawTicks: false },
            ticks: { stepSize: 25, color: '#8b95aa', padding: 10, callback: (value) => `${value}%` },
          },
          x: {
            border: { display: false },
            grid: { display: false },
            ticks: { color: '#526077', font: { size: 11, weight: '600' }, maxRotation: 35, minRotation: 0 },
          },
        },
      },
      plugins: [{
        id: 'studentBarGlow',
        beforeDatasetDraw(chart) {
          chart.ctx.save();
          chart.ctx.shadowColor = 'rgba(92, 80, 224, 0.22)';
          chart.ctx.shadowBlur = 15;
          chart.ctx.shadowOffsetY = 7;
        },
        afterDatasetDraw(chart) { chart.ctx.restore(); },
      }],
    });
  };
})();
