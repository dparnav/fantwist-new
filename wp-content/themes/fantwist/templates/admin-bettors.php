<?php /* Template Name: Admin Bettors */ 
if (is_user_logged_in()) {

    if (current_user_can('administrator')) {

        if (isset($_GET['viewas'])) {

            $current_user_id = $_GET['viewas'];
        } else {

            $current_user_id = get_current_user_id();
        }
    } else {

        $current_user_id = get_current_user_id();
    }
} else {

    header("Location: " . home_url($path = '/admin'));
    exit;
}
?>
<?php get_header('custom'); ?>

<div class="content-wrapper pt-5 pb-2">
    <div class="container">
        <h4 class="mb-4"><?php the_title(); ?></h4>

    </div>
</div>


<!-- Begin Page Content -->
<div class="container">

    <!-- Page Heading -->
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Bettor's Information</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive" style="overflow: hidden;">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>                                   
                            <th>Bets Placed</th>
							<th>Start date</th>
							<th>Bet Amount</th>
                        </tr>
                    </thead>                    
                    <tbody>
                        <tr>
                            <td>Tiger Nixon</td>                                                    
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>$320,800</td>
                        </tr>
                        <tr>
                            <td>Garrett Winters</td>                                             
                            <td>63</td>
                            <td>2011/07/25</td>
                            <td>$170,750</td>
                        </tr>
                        <tr>
                            <td>Ashton Cox</td>                                            
                            <td>66</td>
                            <td>2009/01/12</td>
                            <td>$86,000</td>
                        </tr>
                        <tr>
                            <td>Cedric Kelly</td>                                            
                            <td>22</td>
                            <td>2012/03/29</td>
                            <td>$433,060</td>
                        </tr>
                        <tr>
                            <td>Airi Satou</td>                                           
                            <td>33</td>
                            <td>2008/11/28</td>
                            <td>$162,700</td>
                        </tr>
                        <tr>
                            <td>Brielle Williamson</td>                                                 
                            <td>61</td>
                            <td>2012/12/02</td>
                            <td>$372,000</td>
                        </tr>
                        <tr>
                            <td>Herrod Chandler</td>                                              
                            <td>59</td>
                            <td>2012/08/06</td>
                            <td>$137,500</td>
                        </tr>
                        <tr>
                            <td>Rhona Davidson</td>                                                  
                            <td>55</td>
                            <td>2010/10/14</td>
                            <td>$327,900</td>
                        </tr>                        
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>


<?php get_footer('custom'); ?>