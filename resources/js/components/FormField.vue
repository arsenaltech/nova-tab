<template>

    <tabs >
        <span v-for="tab in availableTabs" :class="{'text-danger': tab.error}">
            <tab :id="tab.hash()" :name="tab.formattedName()"  :key="tab.id" :isError="tab.error">
                <component
                  :class="{'remove-bottom-border': index == tab.fields.length - 1}"
                  :key="index"
                  v-for="(field, index) in tab.fields"
                  :is="resolveComponentName(field)"
                  :resource-name="resourceName"
                  :resource-id="resourceId"
                  :resource="resource"
                  :field="field"
                  :errors="errors"
                  :via-resource="viaResource"
                  :via-resource-id="viaResourceId"
                  :via-relationship="viaRelationship"
                  @file-deleted="file-deleted"
                />
            </tab>
        </span>

    </tabs>
</template>

<script>
  import { FormField, HandlesValidationErrors, InteractsWithResourceInformation } from 'laravel-nova'
  export default {
    mixins: [FormField, HandlesValidationErrors, InteractsWithResourceInformation],
    props: ['field', 'resourceId', 'resourceName', 'errors', 'viaResource', 'viaRelationship'],
    data: () => ({
      fields: [],
    }),
    computed: {
      /**
       * Get the available field panels.
       */
      availableTabs() {
        var tabs = {}

        this.field.fields.forEach(field => {
          if (tabs[field.tab]) {
            tabs[field.tab].fields.push(field)
          }
          else {
            tabs[field.tab] = this.createTabForField(field)
          }
          if(this.errors.errors[field.attribute] )  {
            tabs[field.tab].error = true
          }
        })

        return _.toArray(tabs)


      }},
    methods: {

      createTabForField(field) {
        return {
          fields: [field],
          name: field.tab,
          id: 'tab-'+field.tab,
          error: false,
          hash() {
            return this.name.toLowerCase().replace(/ /g, '-')
          },
          formattedName() {
              if(this.error) {
                return '<span class="text-danger bounce">'+this.name+'</span>'
              }
              return this.name
            }
        }
      },
      resolveComponentName(field) {
        return field.prefixComponent ? 'form-' + field.component : field.component
      },
      /**
        * Set the initial, internal value for the field.
        */
      setInitialValue() {
        this.value = this.field.value || ''
      },

      /**
       * Fill the given FormData object with the field's internal value.
       */
      fill(formData) {
        console.log(this.fields)
        _.each(this.field.fields, field => {
          field.fill(formData)
        })
      },

      /**
       * Update the field's internal value.
       */
      handleChange(value) {
        this.value = value
      }
    }
  }
</script>
