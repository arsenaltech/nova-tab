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
                  :dusk="tab.hash()"
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
    props: ['field', 'resourceId', 'resourceName', 'errors', 'viaResource', 'viaRelationship', 'viaResourceId'],
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
          if (tabs[field.tab.name]) {
            tabs[field.tab.name].fields.push(field)
          }
          else {
            tabs[field.tab.name] = this.createTabForField(field)
          }
          if(this.errors.errors[field.attribute] )  {
            tabs[field.tab.name].error = true
          }
        })
        return _.toArray(tabs)


      }},
    methods: {

      createTabForField(field) {
        return {
          fields: [field],
          name: field.tab.name,
          id: 'tab-'+field.tab.name,
          html: field.tab.html,
          error: false,
          hash() {
            return this.name.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, "").replace(/ /g, '-')
          },
          formattedName() {
              if(this.error) {
                return '<span class="text-danger bounce">'+this.html+'</span>'
              }
              return this.html
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
