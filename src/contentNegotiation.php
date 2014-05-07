<?php


/**
 * Class contentNegotiation, to decide what content return.
 */
class contentNegotiation
{
    /**
     * For a concrete user priorities return the most valuable and available value.
     *
     * @param string $user_priorities The user priorities.
     * @param array $available_values The current values available.
     * @return string
     */
    public function getAvailable( $user_priorities, array $available_values = array() )
    {
        if ( empty( $user_priorities ) || empty( $available_values  ) )
        {
            return null;
        }

        $value_selected     = null;
        $max_prio           = -1;

        foreach ( explode( ',', $user_priorities ) as $user_priority )
        {
            $user_priority  = explode( ';', $user_priority );
            
            $value          = $user_priority[0];
            $current_prio   = isset( $user_priority[1] )? $user_priority[1] : 1;

            if ( $this->isCandidateFormat( $available_values, $value, $max_prio, $current_prio ) )
            {
                $value_selected = $value;
                $max_prio       = $current_prio;
            }
        }

        return $value_selected;
    }

    /**
     * Indicate if it's candidate, that means, exists in the available and has highest prio.
     *
     * @param array $available_values The array with tha available values.
     * @param string $value The current value to check.
     * @param integer $max_prio The max prio until the moment.
     * @param integer $current_prio The prio of the current value.
     * @return boolean
     */
    private function isCandidateFormat( array $available_values, $value, $max_prio, $current_prio )
    {
        return in_array( $value, $available_values ) && $max_prio < $current_prio;
    }

}