<div class="video-placeholder" style="width: 100%; height: 100%; min-height: 400px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">
    <div class="placeholder-content" style="text-align: center; color: white; padding: 20px; z-index: 2;">
        <div class="video-icon" style="font-size: 80px; margin-bottom: 20px; animation: pulse 2s infinite;">
            🎬
        </div>
        <h3 style="font-size: 24px; margin-bottom: 12px; font-weight: 600;">Video not available</h3>
        <p style="font-size: 16px; opacity: 0.9; margin-bottom: 24px;">File missing</p>
        <div class="hint-text" style="font-size: 14px; opacity: 0.7; border-top: 1px solid rgba(255,255,255,0.3); padding-top: 20px; display: inline-block;">
            ⚠️ Проверьте настройки видео
        </div>
    </div>
    <!-- Декоративные круги на фоне -->
    <div style="position: absolute; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%; top: -50px; right: -50px;"></div>
    <div style="position: absolute; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%; bottom: -30px; left: -30px;"></div>
    
    <style>
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }
        
        .video-placeholder:hover .video-icon {
            animation: pulse 1s infinite;
        }
    </style>
</div>