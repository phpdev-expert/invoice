<?php $presenter = new Illuminate\Pagination\BootstrapPresenter($paginator); ?>

<?php if ($paginator->getLastPage() > 1)
{ ?>
    <ul class="pagination pagination-sm no-margin">
        <?php echo $presenter->render(); ?>
    </ul>
<?php } ?>