<div class="form">
    <table>
        <?php for ($row = 0; $row < $game->getSide(); $row++): ?>
        <tr>
            <?php for ($col = 0; $col < $game->getSide(); $col++): ?>
                <td>
                    <button name="btn-<?=$row?>-<?=$col?>" type="submit"><?= $game->getValue($row, $col) ?></button> 
                    
                    <input style="display:none" type="text" name="<?=$row?>-<?=$col?>" 
                        value="<?= $game->getValue($row, $col) ?>"> 
                </td>
            <?php endfor; ?>
        </tr>
        <?php endfor; ?>
    </table>
    <button name="next" value="next" type="submit">NEXT</button>
</div>