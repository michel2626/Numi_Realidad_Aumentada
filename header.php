<?php
// Aseguramos que la sesión esté activa en cualquier página que use el menú
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php if(isset($_SESSION['usuario_nombre'])): ?>
                                            
    <div style="display: flex; align-items: center;">
        <div style="width: 48px; height: 48px; background-color: <?php echo (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') ? '#EEB4C5' : '#51CCCC'; ?>; border-radius: 50%; overflow: hidden; border: 2px solid white; margin-right: 12px;">
            <img src="imagenes/contento.png" alt="Perfil" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        
        <div style="display: flex; flex-direction: column; justify-content: center; line-height: 1.3;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="font-weight: bold; color: white; font-size: 17px; letter-spacing: 0.3px;">
                    <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
                </span>
                
                <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                    <span style="font-size: 11px; font-weight: 800; color: #141022; background: #EEB4C5; padding: 3px 8px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Admin</span>
                <?php else: ?>
                    <span style="font-size: 12px; font-weight: 700; color: white; background: rgba(255, 255, 255, 0.25); padding: 3px 9px; border-radius: 6px; letter-spacing: 0.3px;">Estudiante</span>
                <?php endif; ?>
            </div>
            
            <div style="display: flex; align-items: center; gap: 10px; margin-top: 3px;">
                <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                    <a href="paneladmin.php" style="font-size: 11px; font-weight: bold; color: #A188DD; background: white; padding: 2px 6px; border-radius: 4px; text-decoration: none;">PANEL</a>
                <?php endif; ?>
                <a href="logout.php" style="font-size: 12px; text-decoration: underline; color: #f0f0f0; opacity: 0.9;">Salir</a>
            </div>
        </div>
    </div>

<?php else: ?>
    <?php endif; ?>