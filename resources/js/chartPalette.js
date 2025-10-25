/**
 * Royal Maids Brand Color Palette for Chart.js
 * 
 * This module provides consistent brand colors for all charts
 * in the Royal Maids application.
 */

// Brand Color Palette
export const brandColors = {
    // Primary Brand Colors
    primary: '#3B0A45',        // Royal Purple
    secondary: '#512B58',      // Deep Violet
    accent: '#F5B301',         // Gold
    
    // Text Colors
    textPrimary: '#FFFFFF',    // White
    textSecondary: '#D1C4E9',  // Light Gray
    
    // Semantic Colors
    success: '#4CAF50',        // Emerald Green
    warning: '#FFC107',        // Amber
    error: '#E53935',          // Soft Red
    info: '#64B5F6',           // Sky Blue
    
    // Package Colors
    silver: '#A8A9AD',         // Silver
    goldPackage: '#FFD700',    // Gold Package
    platinum: '#B9A0DC',       // Platinum
};

// Chart.js Color Palettes
export const chartPalettes = {
    // Primary palette for main charts
    primary: [
        brandColors.accent,        // Gold
        brandColors.info,          // Sky Blue
        brandColors.success,       // Emerald Green
        brandColors.warning,       // Amber
        brandColors.error,         // Soft Red
        brandColors.platinum,      // Platinum
    ],
    
    // Package-specific palette
    packages: [
        brandColors.silver,        // Silver
        brandColors.accent,        // Gold
        brandColors.platinum,      // Platinum
    ],
    
    // Status palette for different states
    status: [
        brandColors.success,       // Active/Completed
        brandColors.warning,       // Pending/In Progress
        brandColors.error,         // Error/Cancelled
        brandColors.info,          // Info/Neutral
    ],
    
    // Performance metrics palette
    performance: [
        brandColors.accent,        // Gold (primary metric)
        brandColors.info,          // Blue (secondary metric)
        brandColors.success,       // Green (positive metric)
        brandColors.platinum,      // Purple (tertiary metric)
    ],
    
    // Revenue and financial palette
    financial: [
        brandColors.accent,        // Gold (revenue)
        brandColors.success,       // Green (profit)
        brandColors.info,          // Blue (growth)
        brandColors.warning,       // Amber (warning)
    ]
};

// Chart.js configuration helpers
export const chartConfig = {
    // Default chart options with brand theming
    defaultOptions: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    color: brandColors.textPrimary,
                    font: {
                        family: 'Instrument Sans, ui-sans-serif, system-ui, sans-serif'
                    }
                }
            },
            tooltip: {
                backgroundColor: brandColors.secondary,
                titleColor: brandColors.textPrimary,
                bodyColor: brandColors.textSecondary,
                borderColor: brandColors.accent,
                borderWidth: 1,
                cornerRadius: 8,
                displayColors: true,
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                        return `${context.label}: ${context.parsed} (${percentage}%)`;
                    }
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: brandColors.textSecondary
                },
                grid: {
                    color: `${brandColors.accent}20`
                }
            },
            y: {
                ticks: {
                    color: brandColors.textSecondary
                },
                grid: {
                    color: `${brandColors.accent}20`
                },
                beginAtZero: true
            }
        }
    },
    
    // Dark mode specific options
    darkModeOptions: {
        ...this.defaultOptions,
        plugins: {
            ...this.defaultOptions.plugins,
            legend: {
                ...this.defaultOptions.plugins.legend,
                labels: {
                    ...this.defaultOptions.plugins.legend.labels,
                    color: brandColors.textPrimary
                }
            }
        }
    }
};

// Utility functions
export const chartUtils = {
    // Get color with opacity
    withOpacity: (color, opacity = 1) => {
        const hex = color.replace('#', '');
        const r = parseInt(hex.substr(0, 2), 16);
        const g = parseInt(hex.substr(2, 2), 16);
        const b = parseInt(hex.substr(4, 2), 16);
        return `rgba(${r}, ${g}, ${b}, ${opacity})`;
    },
    
    // Generate gradient background
    createGradient: (ctx, color1, color2, direction = 'vertical') => {
        const gradient = direction === 'vertical' 
            ? ctx.createLinearGradient(0, 0, 0, 400)
            : ctx.createLinearGradient(0, 0, 400, 0);
        gradient.addColorStop(0, color1);
        gradient.addColorStop(1, color2);
        return gradient;
    },
    
    // Get random color from palette
    getRandomColor: (palette = 'primary') => {
        const colors = chartPalettes[palette] || chartPalettes.primary;
        return colors[Math.floor(Math.random() * colors.length)];
    }
};

// Pre-configured chart datasets
export const chartDatasets = {
    // Line chart dataset
    line: (label, data, color = brandColors.accent) => ({
        label,
        data,
        borderColor: color,
        backgroundColor: chartUtils.withOpacity(color, 0.1),
        tension: 0.4,
        fill: true,
        pointBackgroundColor: color,
        pointBorderColor: brandColors.textPrimary,
        pointBorderWidth: 2,
        pointRadius: 4,
        pointHoverRadius: 6
    }),
    
    // Bar chart dataset
    bar: (label, data, color = brandColors.accent) => ({
        label,
        data,
        backgroundColor: chartUtils.withOpacity(color, 0.8),
        borderColor: color,
        borderWidth: 1,
        borderRadius: 4,
        borderSkipped: false
    }),
    
    // Doughnut chart dataset
    doughnut: (labels, data, colors = chartPalettes.packages) => ({
        labels,
        datasets: [{
            data,
            backgroundColor: colors,
            borderColor: brandColors.textPrimary,
            borderWidth: 2,
            hoverOffset: 4
        }]
    }),
    
    // Pie chart dataset
    pie: (labels, data, colors = chartPalettes.primary) => ({
        labels,
        datasets: [{
            data,
            backgroundColor: colors,
            borderColor: brandColors.textPrimary,
            borderWidth: 2,
            hoverOffset: 4
        }]
    })
};

export default {
    brandColors,
    chartPalettes,
    chartConfig,
    chartUtils,
    chartDatasets
};
